<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;
use Module\Shop\Domain\Models\Scene\Coupon;

class CouponController extends Controller {

    protected function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction($category = 0) {
        $time = time();
        $coupon_list = Coupon::where('send_type', 0)
            ->when($category > 0, function ($query) use ($category) {
                $query->where('rule', CouponModel::RULE_CATEGORY)
                    ->where('rule_value', $category);
            })->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)
            ->page();
        return $this->renderPage($coupon_list);
    }

    public function myAction($status = 0) {
        $ids = CouponLogModel::where('user_id', auth()->id())
            ->when($status < 1 || $status == 2, function ($query) {
                $query->where('used_at', 0);
            })
            ->when($status == 1, function ($query) {
                $query->where('used_at', '>', 0);
            })->pluck('coupon_id');
        $coupon_list = CouponModel::whereIn('id', $ids)
            ->when($status == 2, function ($query) {
                $query->where('end_at', '<', time());
            })->page();
        return $this->renderPage($coupon_list);
    }

    public function receiveAction($id) {
        $coupon = CouponModel::find($id);
        if (!$coupon || !$coupon->can_receive) {
            return $this->renderFailure('领取失败!');
        }
        CouponLogModel::create([
            'user_id' => auth()->id(),
            'coupon_id' => $coupon->id
        ]);
        return $this->render(['data' => true]);
    }
}