<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\AddressModel;

class AddressController extends Controller {

    public function indexAction() {
        $model_list = AddressModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = AddressModel::findOrNew($id);
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new AddressModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('address')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        AddressModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('address')
        ]);
    }
}