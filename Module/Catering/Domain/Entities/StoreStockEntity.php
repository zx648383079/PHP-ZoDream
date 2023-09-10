<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺库存
 * @property integer $id
 * @property integer $store_id
 * @property integer $cat_id
 * @property integer $material_id
 * @property float $amount
 * @property integer $unit
 * @property integer $updated_at
 * @property integer $created_at
 */
class StoreStockEntity extends Entity {
    public static function tableName(): string {
        return 'eat_store_stock';
    }


    protected function rules(): array {
        return [
            'store_id' => 'required|int',
            'cat_id' => 'required|int',
            'material_id' => 'required|int',
            'amount' => 'required|string',
            'unit' => 'required|int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'cat_id' => 'Cat Id',
            'material_id' => 'Material Id',
            'amount' => 'Amount',
            'unit' => 'Unit',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}