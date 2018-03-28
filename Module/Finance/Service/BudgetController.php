<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class BudgetController extends ModuleController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = BudgetModel::where('deleted_at', 0)->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function addAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = BudgetModel::findOrNew($id);
        return $this->show('create', compact('model'));
    }

    public function saveAction() {
        $model = new BudgetModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            $model->refreshSpent();
            return $this->jsonSuccess([
                'url' => (string)Url::to('./budget')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BudgetModel::where('id', $id)->update([
            'deleted_at' => time()
        ]);
        return $this->jsonSuccess([
            'url' => (string)Url::to('./budget')
        ]);
    }
}