<?php
namespace Module\Shop\Domain\Model;

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
 * @property integer $type
 * @property integer $user_id
 * @property string $data
 * @property integer $status
 * @property float $amount
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
            'type' => 'int:0,9',
            'user_id' => 'required|int',
            'data' => 'string:0,255',
            'status' => 'int:0,9',
            'amount' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'payment_id' => 'Payment Id',
            'type' => 'Type',
            'user_id' => 'User Id',
            'data' => 'Data',
            'status' => 'Status',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function pay() {
        $this->status = self::STATUS_SUCCESS;
        $this->save();
        if ($this->type != self::TYPE_ORDER) {
            return true;
        }
        $order_list = OrderModel::whereIn('id', explode(',', $this->data))->get();
        foreach ($order_list as $order) {
            if ($order->status != OrderModel::STATUS_UN_PAY) {
                continue;
            }
            OrderLogModel::pay($order);
        }
        return true;
    }
}