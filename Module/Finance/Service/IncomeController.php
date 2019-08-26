<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Helpers\Time;
use Zodream\Service\Factory;


class IncomeController extends Controller {

    public function indexAction($month = null) {
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

    public function logAction($type = null, $keywords = null) {
        $log_list = LogModel::auth()
            ->when(is_numeric($type), function ($query) use ($type) {
            $query->where('type', intval($type));
        })->when(!empty($keywords), function ($query) {
                LogModel::search($query, 'remark');
            })->orderBy('happened_at', 'desc')->page();
        return $this->show(compact('log_list'));
    }

    public function addLogAction() {
        return $this->editLogAction(0);
    }

    public function editLogAction($id) {
        $model = LogModel::findOrNew($id);
        if (empty($model->id)) {
            $model->money = $model->frozen_money = 0;
        }
        if (app('request')->has('clone_id')) {
            $model = LogModel::findOrNew(intval(app('request')->get('clone_id')));
            $model->id = null;
        }
        if (app('request')->has('budget_id')) {
            $model->type = LogModel::TYPE_EXPENDITURE;
            $model->budget_id = intval(app('request')->get('budget_id'));
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

    public function saveLogAction() {
        $model = new LogModel();
        if (!$model->load() || !$model->set('user_id', auth()->id())->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($model->budget_id > 0) {
            BudgetModel::find($model->budget_id)->refreshSpent();
        }
        return $this->jsonSuccess([
            'url' => url('./income/log')
        ]);
    }

    public function deleteLogAction($id) {
        LogModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./income/log')
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
            LogModel::import($file->getFile());
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
        $day, $account_id, $channel_id = 0, $budget_id = 0,
        $breakfast = null, $lunch = null, $dinner = null) {
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

    public function saveChannelAction() {
        $model = new ConsumptionChannelModel();
        if ($model->load() && $model->set('user_id', auth()->id())->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./income/channel')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteChannelAction($id) {
        ConsumptionChannelModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./income/channel')
        ]);
    }
}