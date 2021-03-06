<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Service\Admin\Controller;
use Module\Shop\Domain\Models\CouponModel;

class CouponController extends Controller {

    public function indexAction() {
        $model_list = CouponModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = CouponModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new CouponModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('activity/coupon'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('activity/coupon'), $model->getFirstError());
    }

    public function deleteAction($id) {
        CouponModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/coupon')
        ]);
    }

}