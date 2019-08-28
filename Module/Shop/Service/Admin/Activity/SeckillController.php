<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
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
        $model->scope = 'all';
        $model->configure = 'null';
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('activity/seckill')
            ], '保存成功！');
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ActivityModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }

    public function timeAction($id = 0) {
        $model_list = ActivityTimeModel::orderBy('start_at asc')->all();
        return $this->show(compact('model_list', 'id'));
    }

    public function createTimeAction() {
        return $this->runMethodNotProcess('editTime', ['id' => null]);
    }

    public function editTimeAction($id) {
        $model = ActivityTimeModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveTimeAction() {
        $model = new ActivityTimeModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('activity/seckill/time')
            ], '保存成功！');
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteTimeAction($id) {
        ActivityTimeModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }

    public function goodsAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)->page();
        return $this->show(compact('model_list'));
    }


}