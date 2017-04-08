<?php
namespace Module\Auction\Domain\Model;


use Domain\Model\Model;

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
}