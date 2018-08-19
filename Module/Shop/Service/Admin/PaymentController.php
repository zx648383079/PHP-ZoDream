<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Model\PaymentModel;
use Module\Shop\Domain\Model\ShippingModel;

class PaymentController extends Controller {

    public function indexAction() {
        $model_list = PaymentModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = PaymentModel::findOrNew($id);
        $pay_list = PaymentModel::paymentList();
        $shipping_list = ShippingModel::select('id', 'name')->all();
        return $this->show(compact('model', 'pay_list', 'shipping_list'));
    }

    public function saveAction() {
        $model = new PaymentModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('payment')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PaymentModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('payment')
        ]);
    }
}