<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;

/**
 * Class SeckillGoodsModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property integer $act_id
 * @property integer $time_id
 * @property integer $goods_id
 * @property float $price
 * @property integer $amount
 * @property integer $every_amount
 */
class SeckillGoodsModel extends Model {

    public static function tableName() {
        return 'shop_seckill_goods';
    }

    protected function rules() {
        return [
            'act_id' => 'required|int',
            'time_id' => 'required|int',
            'goods_id' => 'required|int',
            'price' => '',
            'amount' => 'int:0,99999',
            'every_amount' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'act_id' => 'Act Id',
            'time_id' => 'Time Id',
            'goods_id' => 'Goods Id',
            'price' => 'Price',
            'amount' => 'Amount',
            'every_amount' => 'Every Amount',
        ];
    }
}