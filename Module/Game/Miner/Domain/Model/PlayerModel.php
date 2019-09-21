<?php
namespace Module\Game\Miner\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\Model\AccountLogModel;
use Exception;
use PhpParser\Node\Expr\Empty_;

/**
 * Class PlayerModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $house_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property HouseModel $house
 */
class PlayerModel extends Model {
    public static function tableName() {
        return 'game_miner_player';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'string:0,255',
            'house_id' => 'int:0,9999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'house_id' => 'House Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function house() {
        return $this->hasOne(HouseModel::class, 'id', 'house_id');
    }

    /**
     *
     * @param $id
     * @return PlayerModel
     * @throws Exception
     */
    public static function findById($id) {
        static $items = [];
        if (isset($items[$id])) {
            return $items[$id];
        }
        return $items[$id] = static::find($id);
    }

    /**
     * @return PlayerModel
     * @throws \Exception
     */
    public static function findCurrent() {
        static $model = null;
        if (!empty($model)) {
            return $model;
        }
        $model = static::where('user_id', auth()->id())->first();
        if (!empty($model)) {
            return $model;
        }
        return $model = new static([
            'user_id' => auth()->id(),
            'name' => sprintf('%s 的住宅', auth()->user()->name)
        ]);
    }

    public static function hireMiner($id) {
        $model = static::findCurrent();
        if ($model->house_id < 1) {
            throw new Exception('请购买住宅');
        }
        $count = PlayerMinerModel::where('player_id', $model->id)->count();
        if ($model->house->amount <= $count) {
            throw new Exception('住不下了，请升级住宅');
        }
        $miner = MinerModel::find($id);
        if (empty($miner)) {
            throw new Exception('矿工不存在');
        }
        if (!AccountLogModel::change(
            auth()->id(), 42, $miner->id, -$miner->price,
            sprintf('购买矿工 %s', $miner->name),
            1
        )) {
            throw new Exception('账户余额不足');
        }
        $item = PlayerMinerModel::create([
            'player_id' => $model->id,
            'miner_id' => $miner->id,
            'start_at' => time(),
            'status' => PlayerMinerModel::STATUS_NONE,
            'area_id' => 0
        ]);
        return !empty($item);
    }

    public static function fireMiner($id) {
        $model = static::findCurrent();
        if ($model->house_id < 1) {
            throw new Exception('矿工不存在');
        }
        return PlayerMinerModel::where('player_id', $model->id)->where('id', $id)->delete();
    }

    public static function workMiner($id, $area_id) {
        $model = static::findCurrent();
        if ($model->house_id < 1) {
            throw new Exception('矿工不存在');
        }
        $miner = PlayerMinerModel::where('player_id', $model->id)->where('id', $id)->first();
        if (empty($miner)) {
            throw new Exception('矿工不存在');
        }
        $miner->balance();
        if ($miner->physical_strength < $miner->miner->max_ps / 2) {
            throw new Exception('体力不能小于50%');
        }
        $area = AreaModel::find($area_id);
        if (empty($area)) {
            throw new Exception('矿场不存在');
        }
        if ($area->price > 0
            && !AccountLogModel::change($model->user_id,
                44, $area->id, -$area->price, '购买矿场使用权')) {
            throw new Exception('账户余额不足');
        }
        $miner->area_id = $area_id;
        $miner->status = PlayerMinerModel::STATUS_WORK;
        $miner->start_at = time();
        return $miner->save();
    }

    public static function balanceMiner($id) {
        $model = static::findCurrent();
        if ($model->house_id < 1) {
            throw new Exception('矿工不存在');
        }
        $miner = PlayerMinerModel::where('player_id', $model->id)
            ->where('id', $id)->first();
        if (empty($miner)) {
            throw new Exception('矿工不存在');
        }
        return $miner->balance();
    }


    public static function buyHouse($houseId) {
        $house = HouseModel::find($houseId);
        if (empty($house)) {
            throw new Exception('住宅错误');
        }
        $model = static::findCurrent();
        if ($model->house_id === $house->id) {
            throw new Exception('已有当前住宅');
        }
        if (!AccountLogModel::change(
            auth()->id(), 41, $houseId, -$house->price,
            sprintf('购买住宅 %s', $house->name),
            1
        )) {
            throw new Exception('账户余额不足');
        }
        $model->house_id = $house->id;
        return $model->save();
    }

}