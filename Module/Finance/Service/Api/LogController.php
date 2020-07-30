<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\LogRepository;
use Zodream\Database\Relation;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;


class LogController extends Controller {

    public function indexAction(int $type = 0, string $keywords = '', int $account = 0,
                              int $budget = 0, string $start_at = '', string $end_at = '') {
        $log_list = LogRepository::getList($type, $keywords, $account, $budget, $start_at, $end_at);
        $account_list = AccountRepository::getItems();
        try {
            $log_list = Relation::bindRelation($log_list, $account_list, 'account', ['account_id' => 'id']);
        } catch (\Exception $e) {
        }
        return $this->renderPage($log_list);
    }

    public function detailAction(int $id) {
        try {
            $model = LogRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = LogRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        LogRepository::remove($id);
        return $this->renderData(true);
    }

    public function batchAction(Request $request) {
        $row = LogRepository::batchEdit(
            $request->get('keywords'),
            $request->get('account_id'),
            $request->get('project_id'),
            $request->get('channel_id'),
            $request->get('budget_id'));
        return $this->render([
            'data' => true,
            'message' => sprintf('更新%d条数据', $row)
        ]);
    }

    public function importAction() {
        $upload = new Upload();
        $upload->setDirectory(Factory::root()->directory('data/cache'));
        $upload->upload('file');
        if (!$upload->checkType('csv') || !$upload->save()) {
            return $this->jsonFailure('文件不支持，仅支持gb2312编码的csv文件');
        }
        $upload->each(function (BaseUpload $file) {
            LogRepository::import($file->getFile());
        });
        return $this->renderData(true);
    }

    public function exportAction() {

    }

    public function dayAction(
        string $day, int $account_id, int $channel_id = 0, int $budget_id = 0,
        array $breakfast = [], array $lunch = [], array $dinner = []) {
        $day = date('Y-m-d', strtotime($day));
        $data = [];
        foreach ([$breakfast, $lunch, $dinner] as $item) {
            if (empty($item) || !isset($item['money']) || $item['money'] <= 0) {
                continue;
            }
            $data[] = [
                'type' => LogModel::TYPE_EXPENDITURE,
                'money' => $item['money'],
                'frozen_money' => 0,
                'account_id' => intval($account_id),
                'channel_id' => intval($channel_id),
                'project_id' => 0,
                'budget_id' => intval($budget_id),
                'remark' => $item['remark'],
                'user_id' => auth()->id(),
                'created_at' => time(),
                'updated_at' => time(),
                'happened_at' => sprintf('%s %s', $day, $item['time']),
            ];
        }
        if (!empty($data)) {
            LogModel::query()->insert($data);
        }
        return $this->renderData(true);
    }
}