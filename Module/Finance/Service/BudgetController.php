<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\ModuleController;

class BudgetController extends ModuleController {

    public function indexAction() {
        $model_list = BudgetModel::page();
        return $this->show(compact('model_list'));
    }

    public function saveAction() {
        $model = new BudgetModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => Url::to('./budget')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }
}