<?php
namespace Module\Legwork\Domain\Model;

use Zodream\Helpers\Json;

/**
 * Class OrderModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property integer $provider_id
 * @property integer $user_id
 * @property integer $service_id
 * @property integer $amount
 * @property string $remark
 * @property float $order_amount
 * @property integer $waiter_id
 * @property integer $status
 * @property integer $service_score
 * @property integer $waiter_score
 * @property integer $pay_at
 * @property integer $taking_at
 * @property integer $taken_at
 * @property integer $finish_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderSimpleModel extends OrderModel {

    public function getRemarkAttribute() {
        $items = parent::getRemarkAttribute();
        return array_filter($items, function ($item) {
            return !empty($item['only']);
        });
    }
}