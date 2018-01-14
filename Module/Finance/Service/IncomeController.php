<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\LogModel;
use Module\ModuleController;

class IncomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }

    public function logAction() {
        $log_list = LogModel::page();
        return $this->show(compact('log_list'));
    }

    public function saveLogAction() {
        $model = new LogModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./income/log')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }
}