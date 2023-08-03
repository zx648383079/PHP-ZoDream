<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 采购单
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $status
 * @property float $price
 * @property string $remark
 * @property integer $execute_id
 * @property integer $check_id
 * @property integer $execute_at
 * @property integer $check_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class PurchaseOrderEntity extends Entity {
    public static function tableName() {
        return 'eat_purchase_order';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'price' => 'required|string',
            'remark' => 'string:0,255',
            'execute_id' => 'int',
            'check_id' => 'int',
            'execute_at' => 'int',
            'check_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'price' => 'Price',
            'remark' => 'Remark',
            'execute_id' => 'Execute Id',
            'check_id' => 'Check Id',
            'execute_at' => 'Execute At',
            'check_at' => 'Check At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}