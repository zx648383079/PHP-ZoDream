<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class CouponModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property integer $type
 * @property integer $rule
 * @property integer $rule_value
 * @property float $min_money
 * @property float $money
 * @property integer $send_type
 * @property integer $send_value
 * @property integer $every_amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class  CouponModel extends Model {

    const TYPE_MONEY = 0;  // 优惠
    const TYPE_DISCOUNT = 1; // 折扣

    const RULE_NONE = 0;
    const RULE_GOODS = 1;
    const RULE_CATEGORY = 2;
    const RULE_BRAND = 3;
    const RULE_STORE = 4;

    const SEND_RECEIVE = 0; // 前台领取
    const SEND_GOODS = 1;   // 购买商品
    const SEND_ORDER = 2;   // 订单金额
    const SEND_SIGN = 3;    // 签到
    const SEND_USER = 4;    // 按用户


    public static function tableName() {
        return 'shop_coupon';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'thumb' => 'string:0,255',
            'type' => 'int:0,99',
            'rule' => 'int:0,99',
            'rule_value' => 'int:0,99',
            'min_money' => '',
            'money' => '',
            'send_type' => 'int',
            'send_value' => 'int',
            'every_amount' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'thumb' => 'Thumb',
            'type' => 'Type',
            'rule' => 'Rule',
            'rule_value' => 'Rule Value',
            'min_money' => 'Min Money',
            'money' => 'Money',
            'send_type' => 'Send Type',
            'send_value' => 'Send Value',
            'every_amount' => 'Every Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}