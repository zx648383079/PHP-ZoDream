<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class BudgetController extends ModuleController {

    public function indexAction() {
        $model_list = BudgetModel::page();
        return $this->show(compact('model_list'));
    }

    public function saveAction() {
        $model = new BudgetModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./budget')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BudgetModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => (string)Url::to('./budget')
        ]);
    }
}