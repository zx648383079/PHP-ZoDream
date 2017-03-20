<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

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
 * @property integer update_at
 * @property integer $create_at
 */
class AuctionModel extends Model {
    public static function tableName() {
        return 'auction';
    }
}