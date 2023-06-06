<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $map_id
 * @property integer $item_id
 * @property integer $amount
 * @property integer $expired_at
 * @property integer $refresh_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class MapItemEntity extends Entity {
    public static function tableName() {
        return 'gm_map_item';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'map_id' => 'required|int',
            'item_id' => 'required|int',
            'amount' => 'int',
            'expired_at' => 'int',
            'refresh_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'map_id' => 'Map Id',
            'item_id' => 'Item Id',
            'amount' => 'Amount',
            'expired_at' => 'Expired At',
            'refresh_at' => 'Refresh At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}