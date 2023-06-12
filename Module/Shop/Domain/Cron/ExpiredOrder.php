<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cron;

use Module\Shop\Domain\Cart\Store;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Repositories\OrderRepository;

class ExpiredOrder {

    public function __construct() {
        $this->cancelExpiredOrder();
    }


    /**
     * 未支付订单超时自动取消
     */
    public function cancelExpiredOrder() {
        $expireTime = OrderRepository::orderExpireTime();
        $items = OrderModel::query()
            ->with('goods')
            ->where('status', OrderModel::STATUS_UN_PAY)
            ->where('created_at', '<', time() - $expireTime)
            ->get();
        if (empty($items)) {
            return;
        }
        $store = new Store();
        $store->setStatus(Store::STATUS_ORDER);
        foreach ($items as $item) {
            if ($store->isImpactInventory()) {
                $regionId = intval(OrderAddressModel::where('order_id', $item['id'])->value('region_id'));
                $store->setRegion($regionId);
                $goodsList = OrderGoodsModel::where('order_id', $item['id'])
                    ->get('goods_id', 'product_id', 'amount');
                foreach ($goodsList as $goods) {
                    $store->pushStock($goods->goods_id, $goods->product_id, $goods->amount);
                }
                $store->restore();
            }
            $item->status = OrderModel::STATUS_CANCEL;
            $item->save();
        }
    }
}
