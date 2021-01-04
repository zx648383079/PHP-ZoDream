<?php
namespace Module\Game\Bank\Service\Admin;

use Module\Game\Bank\Domain\Model\BankProductModel;

class ProductController extends Controller {

    public function indexAction() {
        $model_list = BankProductModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = BankProductModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new BankProductModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => url('./@admin/product')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BankProductModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => url('./@admin/product')
        ]);
    }
}