<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Shop\Domain\Auction\AuctionInterface;
use Module\Shop\Domain\Auction\CommonAuction;
use Module\Shop\Domain\Auction\DutchAuction;

/**
 * 拍卖
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $act_id
 * @property integer $user_id
 * @property integer $bid 出价
 * @property integer $amount //数量
 * @property integer $status 竞拍状态 是否成交
 * @property integer $created_at
 *
 * @property ActivityModel $activity
 */
class AuctionLogModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_INVALID = 2; // 无效


    const MODE_COMMON = 0;
    const MODE_DUTCH = 1;

    public static function tableName() {
        return 'shop_auction_log';
    }

    public function activity() {
        return $this->hasOne(ActivityModel::class, 'id', 'act_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    /**
     * @return AuctionInterface
     */
    public function auction() {
        $configure = $this->activity->configure;
        $instance = is_array($configure) &&
            isset($configure['mode']) &&
            $configure['mode'] === static::MODE_DUTCH ?
            new DutchAuction($this->activity, $configure) :
            new CommonAuction($this->activity, $configure);
        $instance->setLog($this);
        return $instance;
    }
}