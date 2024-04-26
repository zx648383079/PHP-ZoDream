<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $name
 * @property string $en_name
 * @property integer $updated_at
 * @property integer $created_at
 */
class GoodsEntity extends Entity {

    public static function tableName(): string {
        return 'tt_goods';
    }

    protected function rules(): array {
		return [
            'name' => 'required|string:0,50',
            'en_name' => 'string:0,50',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'name' => 'Name',
            'en_name' => 'En Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}