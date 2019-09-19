<?php
namespace Module\Game\Miner\Domain\Model;


use Domain\Model\Model;

/**
 * Class HouseModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $amount
 * @property integer $resume_speed
 * @property integer $price
 */
class HouseModel extends Model {
    public static function tableName() {
        return 'game_miner_house';
    }

    protected function rules() {
        return [
            'name' => 'string:0,255',
            'amount' => 'required|int:0,9999',
            'resume_speed' => 'required|int',
            'price' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'amount' => '可住人数',
            'resume_speed' => '体力恢复速度',
            'price' => '价格',
        ];
    }


}