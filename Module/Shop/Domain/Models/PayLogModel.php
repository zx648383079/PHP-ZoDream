<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PayLogModel
 * @property integer $id
 * @property integer $payment_id
 * @property string $payment_name
 * @property integer $type
 * @property integer $user_id
 * @property string $data
 * @property integer $status
 * @property float $amount
 * @property string $currency
 * @property float $currency_money
 * @property string $trade_no
 * @property integer $begin_at
 * @property integer $confirm_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class PayLogModel extends Model {

    const TYPE_ORDER = 0;
    const TYPE_BALANCE = 1;


    const STATUS_NONE = 0;
    const STATUS_WAITING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILURE = 3;


    public static function tableName() {
        return 'shop_pay_log';
    }

    protected function rules() {
        return [
            'payment_id' => 'required|int',
            'payment_name' => 'string:0,30',
            'type' => 'int:0,9',
            'user_id' => 'required|int',
            'data' => 'string:0,255',
            'status' => 'int:0,9',
            'amount' => '',
            'currency' => 'string:0,10',
            'currency_money' => '',
            'trade_no' => 'string:0,100',
            'begin_at' => 'int',
            'confirm_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'payment_id' => 'Payment Id',
            'payment_name' => 'Payment Name',
            'type' => 'Type',
            'user_id' => 'User Id',
            'data' => 'Data',
            'status' => 'Status',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'currency_money' => 'Currency Money',
            'trade_no' => 'Trade No',
            'begin_at' => 'Begin At',
            'confirm_at' => 'Confirm At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}