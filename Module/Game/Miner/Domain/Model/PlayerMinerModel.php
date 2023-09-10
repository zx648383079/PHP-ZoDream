<?php
namespace Module\Game\Miner\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;

/**
 * Class PlayerMinerModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property integer $player_id
 * @property integer $miner_id
 * @property integer $area_id
 * @property integer $physical_strength
 * @property integer $ps
 * @property integer $status
 * @property integer $start_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class PlayerMinerModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_WORK = 1;


    public static function tableName(): string {
        return 'game_miner_player_miner';
    }

    protected function rules(): array {
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

    protected function labels(): array {
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

    public function getLongTimeAttribute() {
        return floor((time() - ($this->start_at <= 0 ? $this->updated_at : $this->start_at)) / 60);
    }

    public function getPsAttribute() {
        $time = $this->long_time;
        if ($time < 1) {
            return $this->physical_strength;
        }
        if ($this->status !== PlayerMinerModel::STATUS_WORK) {
            return min($this->physical_strength + $time * PlayerModel::findById($this->player_id)->house->resume_speed, $this->miner->max_ps);
        }
        return max($this->physical_strength - $time, 0);
    }

    public function getIncomeAttribute() {
        $time = $this->long_time;
        if ($time < 1) {
            return 0;
        }
        $time = min($this->physical_strength, $time);
        return min($time * (
            $this->area->earnings + $this->miner->earnings),
            $this->area->amount, $this->miner->max_money);
    }

    public function balance() {
        if ($this->status !== PlayerMinerModel::STATUS_WORK) {
            $this->balancePS();
            return true;
        }
        $income = $this->income;
        FundAccount::change(PlayerModel::findById($this->player_id)->user_id,
            43, $this->id, $income, sprintf('工作 %s 分钟的收益', $this->long_time));
        $this->start_at = time();
        $this->physical_strength = max(0, $this->physical_strength - $this->long_time);
        $this->status = self::STATUS_NONE;
        $this->area_id = 0;
        $this->area->amount -= $income;
        $this->area->save();
        return $this->save();
    }

    private function balancePS() {
        $this->physical_strength = $this->ps;
        $this->start_at = time();
        $this->save();
    }


}