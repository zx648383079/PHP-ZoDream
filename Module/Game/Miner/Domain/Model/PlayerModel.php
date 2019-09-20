<?php
namespace Module\Game\Miner\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\Model\AccountLogModel;

/**
 * Class PlayerModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $house_id
 * @property integer $created_at
 * @property integer $updated_at
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
     * @return PlayerModel
     * @throws \Exception
     */
    public static function findCurrent() {
        $model = static::where('user_id', auth()->id())->first();
        if (!empty($model)) {
            return $model;
        }
        return new static([
            'user_id' => auth()->id(),
            'name' => sprintf('%s 的住宅', auth()->user()->name)
        ]);
    }

    public static function buyHouse($houseId) {
        $house = HouseModel::find($houseId);
        if (empty($house)) {
            return false;
        }
        $model = static::findCurrent();
        if ($model->house_id === $house->id) {
            return false;
        }
        if (!AccountLogModel::change(
            auth()->id(), 41, $houseId, -$house->price,
            sprintf('购买住宅 %s', $house->name),
            1
        )) {
            return false;
        }
        $model->house_id = $house->id;
        return $model->save();
    }

}