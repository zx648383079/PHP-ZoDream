<?php
namespace Module\Shop\Domain\Cron;

use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderModel;

class ExpiredOrder {

    public function __construct() {
        $this->cancelExpiredOrder();
    }


    /**
     * 未支付订单超时自动取消
     */
    public function cancelExpiredOrder() {
        $items = OrderModel::query()
            ->with('goods')
            ->where('status', OrderModel::STATUS_UN_PAY)
            ->where('created_at', '<', time() - 3600)
            ->get();
        if (empty($items)) {
            return;
        }
        foreach ($items as $item) {
            foreach ($item->goods as $goods) {
                GoodsModel::query()->where('goods_id', $goods->goods_id)
                    ->updateOne('store', $goods->amount);
            }
            $item->status = OrderModel::STATUS_CANCEL;
            $item->save();
        }
    }
}
