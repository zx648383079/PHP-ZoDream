<?php
declare(strict_types=1);
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\ChannelRepository;
use Module\Finance\Domain\Repositories\LogRepository;
use Zodream\Database\Relation;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;


class IncomeController extends Controller {

    public function indexAction(string $month = '') {
        if (empty($month)) {
            $month = date('Y-m');
        }
        $time = strtotime($month);
        $income_list = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->orderBy('happened_at', 'desc')->all();
        $expenditure_list = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->orderBy('happened_at', 'desc')->all();
        $log_list = LogModel::auth()->month($time)->orderBy('happened_at', 'desc')->all();
        $day_length = date('t', $time);
        $income_days = LogModel::getMonthLogs($income_list, $day_length);
        $expenditure_days = LogModel::getMonthLogs($expenditure_list, $day_length);
        return $this->show(compact('month', 'income_days', 'income_list', 'expenditure_list', 'expenditure_days', 'log_list', 'day_length'));
    }

    public function logAction(int $type = 0, string $keywords = '', int $account = 0,
                              int $budget = 0, string $start_at = '', string $end_at = '') {
        $log_list = LogRepository::getList($type, $keywords, $account, $budget, $start_at, $end_at);
        $account_list = AccountRepository::getItems();
        $log_list = Relation::bindRelation($log_list, $account_list, 'account', ['account_id' => 'id']);
        return $this->show(compact('log_list', 'account_list', 'keywords', 'type', 'account'));
    }

    public function addLogAction(Request $request) {
        return $this->editLogAction(0, $request);
    }

    public function editLogAction(int $id, Request $request) {
        try {
            $model = $id > 0 ? LogRepository::get($id) : new LogModel();
            if (empty($model->id)) {
                $model->money = $model->frozen_money = 0;
            }
            if ($request->has('clone_id')) {
                $model = LogRepository::get(intval($request->get('clone_id')));
                $model->id = null;
            }
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./income/log', $ex->getMessage());
        }

        if ($request->has('budget_id')) {
            $model->type = LogModel::TYPE_EXPENDITURE;
            $model->budget_id = intval($request->get('budget_id'));
        }
        if (empty($model->happened_at)) {
            $model->happened_at = Time::format();
        }
        $channel_list = ConsumptionChannelModel::auth()->all();
        $account_list = MoneyAccountModel::auth()->all();
        $project_list = FinancialProjectModel::auth()->all();
        $budget_list = BudgetModel::auth()->where('deleted_at', 0)->all();
        return $this->show('create_log', compact('model', 'channel_list', 'account_list', 'project_list', 'budget_list'));
    }

    public function saveLogAction(Request $request) {
        try {
            $model = LogRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'url' => url('./income/log')
        ]);
    }

    public function deleteLogAction(int $id) {
        LogRepository::remove($id);
        return $this->jsonSuccess([
            'url' => url('./income/log')
        ]);
    }

    public function batchEditLogAction() {
        $channel_list = ConsumptionChannelModel::auth()->all();
        $account_list = MoneyAccountModel::auth()->all();
        $project_list = FinancialProjectModel::auth()->all();
        $budget_list = BudgetModel::auth()->where('deleted_at', 0)->all();
        return $this->show(compact('channel_list', 'account_list', 'project_list', 'budget_list'));
    }

    public function saveBatchLogAction(Request $request) {
        $row = LogRepository::batchEdit(
            $request->get('keywords'),
            $request->get('account_id'),
            $request->get('project_id'),
            $request->get('channel_id'),
            $request->get('budget_id'));
        return $this->jsonSuccess([], sprintf('更新%d条数据', $row));
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
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function exportAction() {

    }

    public function addDayLogAction() {
        $channel_list = ConsumptionChannelModel::auth()->all();
        $account_list = MoneyAccountModel::auth()->all();
        $budget_list = BudgetModel::auth()->where('deleted_at', 0)->all();
        return $this->show('create_day_log', compact('channel_list', 'account_list', 'budget_list'));
    }

    public function saveDayLogAction(
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
        return $this->jsonSuccess([
            'url' => url('./income/log')
        ]);
    }

    public function channelAction(){
        $model_list = ConsumptionChannelModel::auth()->orderBy('id', 'desc')->all();
        return $this->show(compact('model_list'));
    }

    public function saveChannelAction(Request $request) {
        try {
            $model = ChannelRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'url' => url('./income/channel')
        ]);
    }

    public function deleteChannelAction(int $id) {
        ChannelRepository::remove($id);
        return $this->jsonSuccess([
            'url' => url('./income/channel')
        ]);
    }
}