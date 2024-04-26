<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $goods_id
 * @property string $name
 * @property string $en_name
 * @property integer $updated_at
 * @property integer $created_at
 */
class ProductEntity extends Entity {

    public static function tableName(): string {
        return 'tt_products';
    }

    protected function rules(): array {
		return [
            'goods_id' => 'required|int',
            'name' => 'required|string:0,100',
            'en_name' => 'string:0,100',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'name' => 'Name',
            'en_name' => 'En Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}