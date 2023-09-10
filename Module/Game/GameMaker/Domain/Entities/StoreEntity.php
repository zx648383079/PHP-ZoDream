<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $item_id
 * @property integer $amount
 * @property integer $price
 * @property integer $updated_at
 * @property integer $created_at
 */
class StoreEntity extends Entity {
    public static function tableName(): string {
        return 'gm_store';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'item_id' => 'required|int',
            'amount' => 'required|int',
            'price' => 'required|int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'item_id' => 'Item Id',
            'amount' => 'Amount',
            'price' => 'Price',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }


}