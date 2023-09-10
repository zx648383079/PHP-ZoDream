<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料参考价格
 * @property integer $id
 * @property string $material_id
 * @property float $amount
 * @property integer $unit
 * @property float $price
 * @property integer $updated_at
 * @property integer $created_at
 */
class MaterialPriceEntity extends Entity {
    public static function tableName(): string {
        return 'eat_material_price';
    }

    protected function rules(): array {
        return [
            'material_id' => 'required|string:0,255',
            'amount' => 'required|string',
            'unit' => 'required|int:0,127',
            'price' => 'required|string',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'material_id' => 'Material Id',
            'amount' => 'Amount',
            'unit' => 'Unit',
            'price' => 'Price',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}