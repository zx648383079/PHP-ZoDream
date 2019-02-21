<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class OrderRefundModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $order_goods_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property string $title
 * @property integer $amount
 * @property integer $type
 * @property integer $status
 * @property string $reason
 * @property string $description
 * @property string $evidence
 * @property string $explanation
 * @property float $money
 * @property float $order_price
 * @property integer $freight
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderRefundModel extends Model {


    const STATUS_REFUSE = 0;     // 拒绝
    const STATUS_IN_REVIEW = 10; // 审核中
    const STATUS_DEALING = 20;   // 处理中
    const STATUS_REFUNDING = 30;          // 退款中
    const STATUS_FINISH = 40;          // 已完成

    public static function tableName() {
        return 'shop_order_refund';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'order_id' => 'required|int',
            'order_goods_id' => 'int',
            'goods_id' => 'required|int',
            'product_id' => 'int',
            'title' => 'required|string:0,255',
            'amount' => 'int',
            'type' => 'int:0,99',
            'status' => 'int:0,99',
            'reason' => 'string:0,255',
            'description' => 'string:0,255',
            'evidence' => 'string:0,255',
            'explanation' => 'string:0,255',
            'money' => '',
            'order_price' => '',
            'freight' => 'int:0,99',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'order_id' => 'Order Id',
            'order_goods_id' => 'Order Goods Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'title' => 'Title',
            'amount' => '数量',
            'type' => '类型',
            'status' => '状态',
            'reason' => '理由',
            'description' => '说明',
            'evidence' => '图片证据',
            'explanation' => '回复',
            'money' => '申请退款金额',
            'order_price' => '订单总金额',
            'freight' => '退款返回路径',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }
}