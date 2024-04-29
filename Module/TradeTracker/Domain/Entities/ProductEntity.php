<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $en_name
 * @property integer $cat_id
 * @property integer $project_id
 * @property string $unique_code
 * @property integer $is_sku
 * @property integer $updated_at
 * @property integer $created_at
 */
class ProductEntity extends Entity {

    public static function tableName(): string {
        return 'tt_products';
    }

    protected function rules(): array {
		return [
            'parent_id' => 'int',
            'name' => 'required|string:0,100',
            'en_name' => 'string:0,100',
            'cat_id' => 'int',
            'project_id' => 'int',
            'unique_code' => 'string:0,100',
            'is_sku' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'parent_id' => 'Parent Id',
            'name' => 'Name',
            'en_name' => 'En Name',
            'cat_id' => 'Cat Id',
            'project_id' => 'Project Id',
            'unique_code' => 'Unique Code',
            'is_sku' => 'Is Sku',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}