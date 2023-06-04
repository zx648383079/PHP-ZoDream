<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $area_id
 * @property integer $item_id
 * @property integer $amount
 */
class MapAreaItemEntity extends Entity {
    public static function tableName() {
        return 'gm_map_area_item';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'area_id' => 'required|int',
            'item_id' => 'required|int',
            'amount' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'area_id' => 'Area Id',
            'item_id' => 'Item Id',
            'amount' => 'Amount',
        ];
    }
}