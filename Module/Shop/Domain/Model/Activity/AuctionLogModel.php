<?php
namespace Module\Shop\Domain\Model\Activity;


use Domain\Model\Model;
use Module\Shop\Domain\Auction\AuctionInterface;

/**
 * 拍卖
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $auction_id
 * @property integer $user_id
 * @property integer $bid 出价
 * @property integer $number //数量
 * @property integer $status 竞拍状态 是否成交
 * @property integer $create_at
 */
class AuctionLogModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_INVALID = 2; // 无效

    public static function tableName() {
        return 'auction_log';
    }

    /**
     * @return AuctionModel
     */
    public function getAuction() {
        return AuctionModel::findOne($this->auction_id);
    }

    /**
     * @return AuctionInterface
     */
    public function auction() {
        return $this->getAuction()->auction($this);
    }
}