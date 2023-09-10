<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 采购单
 * @property integer $id
 * @property integer $order_id
 * @property integer $material_id
 * @property float $amount
 * @property integer $unit
 * @property float $price
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class PurchaseOrderGoodsEntity extends Entity {
    public static function tableName(): string {
        return 'eat_purchase_order_goods';
    }

    protected function rules(): array {
        return [
            'order_id' => 'required|int',
            'material_id' => 'required|int',
            'amount' => 'required|string',
            'unit' => 'required|int:0,127',
            'price' => 'required|string',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'material_id' => 'Material Id',
            'amount' => 'Amount',
            'unit' => 'Unit',
            'price' => 'Price',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}