<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\LogModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class IncomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
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