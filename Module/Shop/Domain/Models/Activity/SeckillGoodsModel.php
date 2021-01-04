<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;
use Module\Shop\Domain\Models\GoodsSimpleModel;

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

    protected $append = ['goods', 'status'];

    const STATUS_DISABLE = 0; // 已抢光
    const STATUS_BUY = 1;     // 可以买
    const STATUS_WAIT = 2;     // 即将开始

    public static function tableName() {
        return 'shop_seckill_goods';
    }

    public function rules() {
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

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }

    public function getStatusAttribute() {
        return self::STATUS_BUY;
    }
}