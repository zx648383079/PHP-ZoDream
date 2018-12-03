<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Model\ShippingModel;

class ShippingController extends Controller {

    public function indexAction() {
        $model_list = ShippingModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ShippingModel::findOrDefault($id, ['position' => 60]);
        $shipping_list = ShippingModel::shippingList();
        return $this->show(compact('model', 'shipping_list'));
    }

    public function saveAction() {
        $model = new ShippingModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('shipping')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ShippingModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('shipping  ')
        ]);
    }
}