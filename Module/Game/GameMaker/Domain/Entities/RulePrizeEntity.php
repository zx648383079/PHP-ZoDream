<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $item_id
 * @property integer $probability
 */
class RulePrizeEntity extends Entity {
    public static function tableName() {
        return 'gm_rule_prize';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'item_id' => 'required|int',
            'probability' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'item_id' => 'Item Id',
            'probability' => 'Probability',
        ];
    }
}