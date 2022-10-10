<?php
namespace Module\Shop\Domain\Plugin\Affiliate;


use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\UserModel;
use Module\SEO\Domain\Model\OptionModel;
use Module\Shop\Domain\Models\OrderModel;
use Zodream\Helpers\Json;

final class AffiliateRepository {

    const OPTION_CODE = 'affiliate';

    public static function getList(string $keywords = '', int $user = 0, int $status = 0) {
        return AffiliateLogModel::with('user')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('order_sn', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })->orderBy('id', 'desc')->page();
    }

    public static function option() {
        return OptionModel::findCodeJson(self::OPTION_CODE, [
            'by_user' => 0,
            'by_user_next' => 0,
            'by_user_grade' => [],
            'by_order' => 0,
            'by_order_scale' => 0,
        ]);
    }

    public static function isInstalled(): bool {
        $option = static::option();
        return !empty($option);
    }

    public static function saveOption(array $data) {
        OptionModel::insertOrUpdate(self::OPTION_CODE, Json::encode($data), '分销返佣');
        return $data;
    }


    public static function statistics() {
        $is_installed = static::isInstalled();
        $income_today = 0;
        $income_count = 0;
        $display_today = 0;
        $display_count = 0;
        $click_count = 0;
        $click_today = 0;
        return compact('is_installed', 'income_today', 'income_count',
            'display_count', 'display_today', 'click_count', 'click_today');
    }

    /**
     * 确认支付订单创建分佣, 最好放后台计划任务执行
     * @param OrderModel $order
     * @return void
     */
    public static function share(OrderModel $order) {
        if ($order->reference_type != OrderModel::REFERENCE_USER) {
            return;
        }
        $option = self::option();
        if ($option['by_order'] > 0 && $option['by_order_scale'] > 0) {
            // TODO
            self::shareByOrder($order, floatval($option['by_order_scale']));
        }
        if ($option['by_user'] > 0) {
            // TODO
            self::shareByUser($order, floatval($option['by_user_next']), $option['by_user_grade']);
        }
    }

    private static function shareByOrder(OrderModel $order, float $scale) {
        $money = $order->order_amount * $scale / 100;
        if ($money <= 0) {
            return;
        }
        AffiliateLogModel::create([
            'user_id' => $order->reference_id,
            'item_type' => AffiliateLogModel::TYPE_ORDER,
            'item_id' => $order->id,
            'order_sn' => $order->series_number,
            'order_amount' => $order->order_amount,
            'money' => $money,
            'status' => AffiliateLogModel::STATUS_NONE
        ]);
    }

    private static function shareByUser(OrderModel $order, float $nextScale, array $gradeItems) {
        $userId = $order->user_id;
        $exist = [$userId];
        foreach ($gradeItems as $item) {
            $userId = UserModel::where('id', $userId)->value('parent_id');
            if ($userId < 1 || in_array($userId, $exist)) {
                break;
            }
            $exist[] = $userId;
            $scale = floatval($item['scale']);
            $money = $order->order_amount * $scale / 100;
            if ($money <= 0) {
                continue;
            }
            AffiliateLogModel::create([
                'user_id' => $userId,
                'item_type' => AffiliateLogModel::TYPE_ORDER,
                'item_id' => $order->id,
                'order_sn' => $order->series_number,
                'order_amount' => $order->order_amount,
                'money' => $money,
                'status' => AffiliateLogModel::STATUS_NONE
            ]);
        }
        if ($nextScale <= 0 || $userId < 1) {
            return;
        }
        $money = $order->order_amount * $nextScale / 100;
        if ($money <= 0) {
            return;
        }
        while (true) {
            $userId = UserModel::where('id', $userId)->value('parent_id');
            if ($userId < 1 || in_array($userId, $exist)) {
                break;
            }
            $exist[] = $userId;
            AffiliateLogModel::create([
                'user_id' => $userId,
                'item_type' => AffiliateLogModel::TYPE_ORDER,
                'item_id' => $order->id,
                'order_sn' => $order->series_number,
                'order_amount' => $order->order_amount,
                'money' => $money,
                'status' => AffiliateLogModel::STATUS_NONE
            ]);
        }
    }

    /**
     * 确认收货，实际打款佣金
     * @param OrderModel $order
     * @return void
     */
    public static function confirmShare(OrderModel $order) {
        $prePage = 20;
        $begin = 0;
        while (true) {
            $items = AffiliateLogModel::where('item_type', AffiliateLogModel::TYPE_ORDER)
                ->where('item_id', $order->id)
                ->orderBy('id', 'asc')
                ->limit($begin, $prePage)->get();
            foreach ($items as $item) {
                $item->status = AffiliateLogModel::STATUS_ARRIVAL;
                $item->save();
                FundAccount::change($item->user_id, FundAccount::TYPE_AFFILIATE,
                    $item->id, $item->money, '');
            }
            $begin += count($items);
            if (count($items) < $prePage) {
                break;
            }
        }
    }
}
