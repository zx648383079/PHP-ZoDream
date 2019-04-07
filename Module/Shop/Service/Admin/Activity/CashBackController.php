<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Service\Admin\Controller;
use Module\Shop\Domain\Models\CouponModel;

class CashBackController extends Controller {

    public function indexAction() {
        $model_list = CouponModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CouponModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new CouponModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('coupon'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('coupon'), $model->getFirstError());
    }

    public function deleteAction($id) {
        CouponModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('coupon')
        ]);
    }

}