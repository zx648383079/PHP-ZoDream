<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

/**
 * 拍卖
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $auction_id
 * @property integer $user_id
 * @property integer $bid 出价
 * @property integer $create_at
 */
class AuctionLogModel extends Model {
    public static function tableName() {
        return 'auction_log';
    }
}