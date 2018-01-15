<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\LogModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class IncomeController extends ModuleController {

    public function indexAction($month = null) {
        if (empty($month)) {
            $month = date('Y-m');
        }
        $time = strtotime($month);
        $start_at = date('Y-m-01 00:00:00', $time);
        $end_at = date('Y-m-31 00:00:00', $time);
        $income_list = LogModel::where('happened_at', '>=', $start_at)->where('happened_at', '<=', $end_at)->where('type', LogModel::TYPE_INCOME)->orderBy('id desc')->all();
        $expenditure_list = LogModel::where('happened_at', '>=', $start_at)->where('happened_at', '<=', $end_at)->where('type', LogModel::TYPE_EXPENDITURE)->orderBy('id desc')->all();
        $log_list = LogModel::where('happened_at', '>=', $start_at)->where('happened_at', '<=', $end_at)->orderBy('id desc')->all();
        $day_length = date('t', $time);
        $income_days = LogModel::getMonthLogs($income_list, $day_length);
        $expenditure_days = LogModel::getMonthLogs($expenditure_list, $day_length);
        return $this->show(compact('month', 'income_days', 'income_list', 'expenditure_list', 'expenditure_days', 'log_list', 'day_length'));
    }

    public function logAction() {
        $log_list = LogModel::page();
        return $this->show(compact('log_list'));
    }

    public function createLogAction() {
        return $this->editLogAction(0);
    }

    public function editLogAction($id) {
        $model = LogModel::findOrNew($id);
        return $this->show('create_log', compact('model'));
    }

    public function saveLogAction() {
        $model = new LogModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => Url::to('./income/log')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteLogAction($id) {
        LogModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => Url::to('./income/log')
        ]);
    }

    public function channelAction(){
        $model_list = ConsumptionChannelModel::all();
        return $this->show(compact('model_list'));
    }

    public function saveChannelAction() {
        $model = new ConsumptionChannelModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => Url::to('./income/channel')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }
}