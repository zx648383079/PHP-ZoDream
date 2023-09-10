<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $indigenous_id
 * @property integer $user_id
 * @property string $name
 * @property integer $earnings
 * @property integer $price
 * @property integer $max_ps
 * @property integer $max_money
 * @property integer $updated_at
 * @property integer $created_at
 */
class MinerEntity extends Entity {
    public static function tableName(): string {
        return 'gm_miner';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'indigenous_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,255',
            'earnings' => 'required|int',
            'price' => 'int',
            'max_ps' => 'int',
            'max_money' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'indigenous_id' => 'Indigenous Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'earnings' => 'Earnings',
            'price' => 'Price',
            'max_ps' => 'Max Ps',
            'max_money' => 'Max Money',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}