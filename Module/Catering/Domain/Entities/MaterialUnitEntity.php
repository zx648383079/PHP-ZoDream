<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料单位换算
 * @property integer $id
 * @property string $material_id
 * @property float $from_amount
 * @property integer $from_unit
 * @property float $to_amount
 * @property integer $to_unit
 */
class MaterialUnitEntity extends Entity {
    public static function tableName() {
        return 'eat_material_unit';
    }

    protected function rules() {
        return [
            'material_id' => 'required|string:0,255',
            'from_amount' => 'required|string',
            'from_unit' => 'required|int:0,127',
            'to_amount' => 'required|string',
            'to_unit' => 'required|int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'material_id' => 'Material Id',
            'from_amount' => 'From Amount',
            'from_unit' => 'From Unit',
            'to_amount' => 'To Amount',
            'to_unit' => 'To Unit',
        ];
    }
}