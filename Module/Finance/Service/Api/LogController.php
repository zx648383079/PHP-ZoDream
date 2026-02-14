<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\LogRepository;
use Zodream\Database\Relation;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Contracts\Http\Input as Request;



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
            $model = LogRepository::save($request->validate([
                'id' => 'int',
                'parent_id' => 'int',
                'type' => 'int:0,127',
                'money' => '',
                'frozen_money' => '',
                'account_id' => 'required|int',
                'channel_id' => 'int',
                'project_id' => 'int',
                'budget_id' => 'int',
                'remark' => '',
                'happened_at' => 'required',
                'out_trade_no' => 'string:0,100',
                'trading_object' => 'string:0,100',
            ]));
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
        try {
            $row = LogRepository::batchEdit(
                $request->validate([
                    'keywords' => 'required',

                    'account_id' => 'int',
                    'channel_id' => 'int',
                    'project_id' => 'int',
                    'budget_id' => 'int',
                    'trading_object' => 'string:0,100',
                ])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true, sprintf('更新 %d 条数据', $row));
    }

    public function importAction(string $password = '') {
        $upload = new Upload();
        $upload->setDirectory(app_path()->directory('data/cache'));
        $upload->upload('file');
        if (!$upload->checkType(['csv', 'xlsx', 'zip']) || !$upload->save()) {
            return $this->renderFailure('文件不支持，仅支持gb2312编码的csv文件');
        }
        $success = 0;
        $upload->each(function (BaseUpload $file) use (&$success, $password) {
            $success += LogRepository::import($file->getFile(), $password);
        });
        return $this->renderData(true, sprintf('导入 %d 条数据', $success));
    }

    public function exportAction() {
        return response()->export(LogRepository::export(true));
    }

    public function dayAction(
        string $day, int $account_id, int $channel_id = 0, int $budget_id = 0,
        array $breakfast = [], array $lunch = [], array $dinner = []) {
        LogRepository::saveDay($day, $account_id, $channel_id, $budget_id, $breakfast, $lunch, $dinner);
        return $this->renderData(true);
    }

    public function countAction(int $type = 0, string $keywords = '', int $account = 0,
                                int $budget = 0, string $start_at = '', string $end_at = '') {
        return $this->renderData(LogRepository::count($type, $keywords, $account, $budget, $start_at, $end_at));
    }
}