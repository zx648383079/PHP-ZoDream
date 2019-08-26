<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;
use Module\Shop\Domain\Models\CouponModel;

class SeckillController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->type = ActivityModel::TYPE_SEC_KILL;
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('coupon'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('coupon'), $model->getFirstError());
    }

    public function deleteAction($id) {
        ActivityModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('seckill')
        ]);
    }

    public function timeAction() {
        return $this->show(compact('model_list'));
    }

    public function createTimeAction() {
        return $this->show(compact('model_list'));
    }

    public function editTimeAction() {
        return $this->show(compact('model_list'));
    }

    public function deleteTimeAction() {
        return $this->show(compact('model_list'));
    }

    public function goodsAction() {
        return $this->show(compact('model_list'));
    }


}