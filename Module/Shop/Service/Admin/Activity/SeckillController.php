<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Service\Admin\Controller;

class SeckillController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->type = ActivityModel::TYPE_SEC_KILL;
        $model->scope = 'all';
        $model->configure = 'null';
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('activity/seckill')
            ], '保存成功！');
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ActivityModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }

    public function timeAction($id = 0) {
        $model_list = ActivityTimeModel::orderBy('start_at asc')->all();
        return $this->show(compact('model_list', 'id'));
    }

    public function createTimeAction() {
        return $this->editTimeAction(0);
    }

    public function editTimeAction($id) {
        $model = ActivityTimeModel::findOrNew($id);
        return $this->show('editTime', compact('model'));
    }

    public function saveTimeAction() {
        $model = new ActivityTimeModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('activity/seckill/time')
            ], '保存成功！');
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteTimeAction($id) {
        ActivityTimeModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }

    public function goodsAction($act_id, $time_id) {
        $model_list = SeckillGoodsModel::with('goods')->where('act_id', $act_id)->where('time_id', $time_id)->page();
        return $this->show(compact('model_list', 'act_id', 'time_id'));
    }

    public function updateGoodsAction($act_id, $time_id, $goods) {
        $goods = static::parseArrInt($goods);
        $exist = SeckillGoodsModel::where('act_id', $act_id)->where('time_id', $time_id)->pluck('goods_id');
        list($add, $_, $del) = static::splitId($goods, $exist);
        if (!empty($add)) {
            SeckillGoodsModel::query()->insert(array_map(function ($goods) use ($act_id, $time_id) {
                return [
                    'act_id' => $act_id,
                    'time_id' => $time_id,
                    'goods_id' => $goods,
                    'price' => 1,
                    'amount' => 1,
                    'every_amount' => 1,
                ];
            }, $add));
        }
        if (!empty($del)) {
            SeckillGoodsModel::where('act_id', $act_id)->where('time_id', $time_id)->whereIn('goods_id', $del)->delete();
        }
        $this->layout = false;
        $model_list = SeckillGoodsModel::with('goods')->where('act_id', $act_id)->where('time_id', $time_id)->page();
        return $this->show('goodsBody', compact('model_list'));
    }

    public function changeGoodsAction($id, $name, $value) {
        if (!in_array($name, ['price', 'amount', 'every_amount'])) {
            return $this->renderFailure('');
        }
        SeckillGoodsModel::where('id', $id)->update([
            $name => floor($value)
        ]);
        return $this->renderData([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }

    public function deleteGoodsAction($id) {
        SeckillGoodsModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/seckill')
        ]);
    }


}