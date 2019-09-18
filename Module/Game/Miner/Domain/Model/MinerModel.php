<?php
namespace Module\Game\Miner\Domain\Model;

use Domain\Model\Model;

/**
 * Class MinerModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $earnings
 * @property integer $price
 * @property integer $max_ps
 * @property integer $max_money
 */
class MinerModel extends Model {
    public static function tableName() {
        return 'game_miner_miner';
    }
    protected function rules() {
        return [
            'name' => 'string:0,255',
            'earnings' => 'required|int:0,9999',
            'price' => 'int',
            'max_ps' => 'int',
            'max_money' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'earnings' => 'Earnings',
            'price' => 'Price',
            'max_ps' => 'Max Ps',
            'max_money' => 'Max Money',
        ];
    }

}