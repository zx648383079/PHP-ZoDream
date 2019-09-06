<?php
namespace Module\Game\Bank\Service\Admin;

use Module\Game\Bank\Domain\Model\BankProductModel;

class ProductController extends Controller {

    public function indexAction() {
        $model_list = BankProductModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function addProjectAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = BankProductModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveProjectAction() {
        $model = new BankProductModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./admin/product')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteProjectAction($id) {
        BankProductModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./admin/product')
        ]);
    }
}