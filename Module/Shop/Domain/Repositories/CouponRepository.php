<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Entities\CouponEntity;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\Scene\Coupon;
use Zodream\Html\Page;

class CouponRepository {

    public static function getCanReceive(int $category = 0) {
        $time = time();
        return Coupon::where('send_type', 0)
            ->when($category > 0, function ($query) use ($category) {
                $query->where('rule', CouponModel::RULE_CATEGORY)
                    ->where('rule_value', $category);
            })->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)
            ->page();
    }

    public static function getMy(int $status) {
        $ids = CouponLogModel::where('user_id', auth()->id())
            ->when($status < 1 || $status == 2, function ($query) {
                $query->where('used_at', 0);
            })
            ->when($status == 1, function ($query) {
                $query->where('used_at', '>', 0);
            })->pluck('coupon_id');
        if (empty($ids)) {
            return (new Page(0))->setPage([]);
        }
        return CouponModel::whereIn('id', $ids)
            ->when($status == 2, function ($query) {
                $query->where('end_at', '<', time());
            })->when($status < 1, function ($query) {
                $query->where('end_at', '>', time());
            })->page();
    }

    public static function getMyUseGoods(array $goods_list) {
        if (empty($goods_list)) {
            return [];
        }
        $ids = CouponLogModel::where('user_id', auth()->id())->where('used_at', 0)->pluck('coupon_id');
        if (empty($ids)) {
            return [];
        }
        $coupon_list = CouponModel::whereIn('id', $ids)->where('end_at', '>', time())->get();
        if (empty($coupon_list)) {
            return [];
        }
        return array_filter($coupon_list, function($item) use ($goods_list) {
            return static::canUse($item, $goods_list);
        });
    }

    public static function receive(int $id) {
        $coupon = CouponModel::where('id', $id)
        ->where('send_type', CouponModel::SEND_RECEIVE)->first();
        if (!$coupon) {
            throw new \Exception('优惠券错误!');
        }
        if (!$coupon->can_receive) {
            throw new \Exception('领取失败!');
        }
        CouponLogModel::create([
            'user_id' => auth()->id(),
            'coupon_id' => $coupon->id
        ]);
    }

    /**
     * @param CouponEntity $item
     * @param CartModel[] $goods_list
     * @return bool
     */
    public static function canUse(CouponEntity $item, array $goods_list) {
        if ($item->rule == CouponModel::RULE_NONE) {
            return true;
        }
        $range = explode(',', $item->rule_value);
        foreach ($goods_list as $goods) {
            if (static::canUseCheckGoods($item->rule, $range, $goods->goods)) {
                return true;
            }
        }
        return false;
    }

    public static function canUseGoods(CouponEntity $item, GoodsModel $goods) {
        return static::canUseCheckGoods($item->rule, explode(',', $item->rule_value), $goods);
    }

    /**
     * 验证单个商品
     * @param $rule
     * @param array $range
     * @param GoodsModel $goods
     * @return bool
     */
    protected static function canUseCheckGoods($rule, array $range, GoodsModel $goods) {
        if ($rule == CouponModel::RULE_GOODS) {
            return in_array($goods->id, $range);
        }
        if ($rule == CouponModel::RULE_BRAND) {
            return in_array($goods->brand_id, $range);
        }
        if ($rule != CouponModel::RULE_CATEGORY) {
            return true;
        }
        $args = array_intersect($range, CategoryRepository::path($goods->cat_id));
        return !empty($args);
    }

    /**
     * 获取当前商品能领取使用的优惠券
     * @param GoodsModel $goods
     * @return array
     */
    public static function goodsCanReceive(GoodsModel $goods) {
        $time = time();
        $items = Coupon::where('send_type', 0)->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->get();
        if (empty($items)) {
            return [];
        }
        return array_filter($items, function ($item) use ($goods) {
            return CouponRepository::canUseGoods($item, $goods);
        });
    }
}