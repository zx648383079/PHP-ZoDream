<?php
namespace Domain\Model\Shopping;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class OrderModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $sign
 * @property integer $status
 * @property integer $pay_status
 * @property integer $shipping_status
 * @property float $goods_amount
 * @property float pay_id
 * @property float $pay_fee
 * @property float $shipping_fee
 * @property float $amount
 * @property integer $user_id
 * @property string $consignee
 * @property string $tel
 * @property string $address
 * @property string $remark
 * @property integer $pay_at
 * @property integer $shipping_at
 * @property integer $create_at
 */
class OrderModel extends Model {

    public static function tableName() {
        return 'order';
    }

    /**
     * @param OrderGoodsModel[] $allGoods
     * @return float
     */
    public function getGoodsAmount(array $allGoods) {
        $total = 0;
        foreach ($allGoods as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }
}