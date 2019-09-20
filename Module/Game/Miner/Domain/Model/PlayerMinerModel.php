<?php
namespace Module\Game\Miner\Domain\Model;


use Domain\Model\Model;

/**
 * Class PlayerMinerModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property integer $player_id
 * @property integer $miner_id
 * @property integer $area_id
 * @property integer $physical_strength
 * @property integer $status
 * @property integer $start_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class PlayerMinerModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_WORK = 1;


    public static function tableName() {
        return 'game_miner_player_miner';
    }

    protected function rules() {
        return [
            'player_id' => 'required|int',
            'miner_id' => 'required|int',
            'area_id' => 'required|int',
            'physical_strength' => 'int',
            'status' => 'int:0,9',
            'start_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'player_id' => 'Player Id',
            'miner_id' => 'Miner Id',
            'area_id' => 'Area Id',
            'physical_strength' => 'Physical Strength',
            'status' => 'Status',
            'start_at' => 'Start At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function miner() {
        return $this->hasOne(MinerModel::class, 'id', 'miner_id');
    }

    public function area() {
        return $this->hasOne(AreaModel::class, 'id', 'area_id');
    }


}