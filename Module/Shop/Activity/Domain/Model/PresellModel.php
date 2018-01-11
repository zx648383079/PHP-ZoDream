<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

/**
 * 预售
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property float $balance 尾款
 * @property float $deposit 定金
 * @property integer $goods_id
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $pay_start 尾款支付开始时间
 * @property integer $pay_end 尾款支付结束时间
 * @property integer update_at
 * @property integer $create_at
 */
class PresellModel extends Model {
    public static function tableName() {
        return 'presell';
    }
}