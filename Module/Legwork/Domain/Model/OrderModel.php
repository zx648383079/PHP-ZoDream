<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class OrderModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $service_id
 * @property string $remark
 * @property string $amount
 * @property float $order_amount
 * @property integer $runner
 * @property integer $status
 * @property integer $service_rank
 * @property integer $pay_at
 * @property integer $taking_at
 * @property integer $taken_at
 * @property integer $finish_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderModel extends Model {

    const STATUS_CANCEL = 1;
    const STATUS_INVALID = 2;
    const STATUS_UN_PAY = 10;
    const STATUS_PAYING = 12;
    const STATUS_PAID_UN_TAKING = 20; // 已支付待接单
    const STATUS_TAKING_UN_DO = 40; // 已接单待做
    const STATUS_TAKEN = 60; // 已完成服务
    const STATUS_FINISH = 80;    // 客户已评价
    const STATUS_REFUNDED = 81;

    public static $status_list = [
        self::STATUS_UN_PAY => '待支付',
        self::STATUS_PAYING => '支付中',
        self::STATUS_TAKING_UN_DO => '待取件',
        self::STATUS_FINISH => '已完成',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_INVALID => '已失效',
        self::STATUS_PAID_UN_TAKING => '待接单',
        self::STATUS_TAKEN => '待评价',
        self::STATUS_REFUNDED => '已退款'
    ];

    protected $append = ['service', 'status_label'];

    public static function tableName() {
        return 'leg_order';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'service_id' => 'required|int',
            'remark' => 'required',
            'amount' => 'int',
            'order_amount' => '',
            'runner' => 'int',
            'status' => 'int:0,127',
            'service_rank' => 'int:0,127',
            'pay_at' => 'int',
            'taking_at' => 'int',
            'taken_at' => 'int',
            'finish_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'service_id' => 'Service Id',
            'amount' => 'Amount',
            'remark' => 'Remark',
            'order_amount' => 'Order Amount',
            'runner' => 'Runner',
            'status' => 'Status',
            'service_rank' => 'Service Rank',
            'pay_at' => 'Pay At',
            'taking_at' => 'Taking At',
            'taken_at' => 'Taken At',
            'finish_at' => 'Finish At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function service() {
        return $this->hasOne(ServiceSimpleModel::class, 'id', 'service_id');
    }

    public function getStatusLabelAttribute() {
        return self::$status_list[$this->status];
    }

    public function getRemarkAttribute() {
        $setting = $this->getAttributeValue('remark');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setRemarkAttribute($value) {
        $this->__attributes['remark'] = is_array($value) ?
            Json::encode($value) : $value;
    }
}