<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Zodream\Helpers\Time;



class IncomeController extends Controller {

    public function indexAction($month = null) {
        if (empty($month)) {
            $month = date('Y-m');
        }
        $time = strtotime($month);
        $income_list = LogModel::month($time)->where('type', LogModel::TYPE_INCOME)->orderBy('happened_at', 'desc')->all();
        $expenditure_list = LogModel::month($time)->where('type', LogModel::TYPE_EXPENDITURE)->orderBy('happened_at', 'desc')->all();
        $log_list = LogModel::month($time)->orderBy('happened_at', 'desc')->all();
        $day_length = date('t', $time);
        $income_days = LogModel::getMonthLogs($income_list, $day_length);
        $expenditure_days = LogModel::getMonthLogs($expenditure_list, $day_length);
        return $this->show(compact('month', 'income_days', 'income_list', 'expenditure_list', 'expenditure_days', 'log_list', 'day_length'));
    }

    public function logAction($type = null) {
        $log_list = LogModel::when(is_numeric($type), function ($query) use ($type) {
            $query->where('type', intval($type));
        })->orderBy('happened_at', 'desc')->page();
        return $this->show(compact('log_list'));
    }

    public function addLogAction() {
        return $this->editLogAction(0);
    }

    public function editLogAction($id) {
        $model = LogModel::findOrNew($id);
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
        $channel_list = ConsumptionChannelModel::all();
        $account_list = MoneyAccountModel::all();
        $project_list = FinancialProjectModel::all();
        $budget_list = BudgetModel::where('deleted_at', 0)->all();
        return $this->show('create_log', compact('model', 'channel_list', 'account_list', 'project_list', 'budget_list'));
    }

    public function saveLogAction() {
        $model = new LogModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
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

    public function channelAction(){
        $model_list = ConsumptionChannelModel::orderBy('id', 'desc')->all();
        return $this->show(compact('model_list'));
    }

    public function saveChannelAction() {
        $model = new ConsumptionChannelModel();
        if ($model->load() && $model->autoIsNew()->save()) {
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