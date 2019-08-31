<?php
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Service\Api\Controller;

class SeckillController extends Controller {

    public function indexAction($id = 0) {
        if (!is_array($id) && $id > 0) {
            return $this->infoAction($id);
        }
        $page = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)->page();
        return $this->renderPage($page);
    }

    public function infoAction($id) {
        $data = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)->where('id', $id)->first();
        if (empty($data)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($data);
    }

    public function goodsAction($act_id = 0, $time_id = 0, $time = null) {
        $data = SeckillGoodsModel::with('goods')
            ->when($act_id > 0, function ($query) use ($act_id) {
                $query->where('act_id', $act_id);
            })->when($time_id > 0, function ($query) use ($time_id) {
                $query->where('time_id', $time_id);
            })->when(!empty($time), function ($query) use ($time) {
                $time = strtotime($time);
                $ids = ActivityModel::where('start_at', '<=', $time)
                    ->where('end_at', '>', $time)->pluck('id');
                $time_ids = ActivityTimeModel::where('start_at', date('H:i', $time))->pluck('id');
                $query->whereIn('act_id', $ids)->whereIn('time_id', $time_ids);
            })->page();
        return $this->renderPage($data);
    }

    public function timeAction() {
        $model_list = ActivityTimeModel::getTimeList();
        return $this->render($model_list);
    }
}