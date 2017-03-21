<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;
use Zodream\Domain\Access\Auth;
use Zodream\Service\Factory;

/**
 * 拍卖
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property float $fixed_price 一口价
 * @property float $initial_price 起拍价
 * @property float $plus_price 加价
 * @property float $deposit 保证金
 * @property integer $goods_id
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $status  状态
 * @property integer $user_id 成交者
 * @property float $max_price  成交价
 * @property integer update_at
 * @property integer $create_at
 */
class AuctionModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_END = 1;

    public static function tableName() {
        return 'auction';
    }

    /**
     * 判断是否允许拍卖
     * @return bool
     */
    public function canAuction() {
        $time = time();
        return $time >= $this->start_at
            && $time <= $this->end_at
            && $this->status === self::STATUS_NONE;
    }

    public function getMaxLog() {
        return AuctionLogModel::findOne([
            'where' => ['auction_id' => $this->id],
            'order' => 'bid desc'
        ]);
    }

    public function auction($bid) {
        if (Auth::guest()) {
            return false;
        }
        if (!$this->canAuction()) {
            return false;
        }
        $user_id = Factory::user()->getId();
        $log = $this->getMaxLog();
        $currentPrice = $this->initial_price;
        if (!empty($log)) {
            $currentPrice = $log->bid;
        }
        if (!empty($log) && $log->user_id == $user_id) {
            return false;
        }
        if ($currentPrice + $this->plus_price > $bid) {
            return false;
        }
        $model = new AuctionLogModel();
        $model->create_at = time();
        $model->user_id = $user_id;
        $model->auction_id = $this->id;
        $model->bid = $bid;
        $result = $model->save();
        if (empty($result)) {
            return false;
        }
        if ($this->fixed_price === 0 || $this->fixed_price > $bid) {
            return $result;
        }
        $this->user_id = $user_id;
        $this->max_price = $bid;
        $this->status = self::STATUS_END;
        return $this->update();
    }


}