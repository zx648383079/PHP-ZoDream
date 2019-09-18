<?php
namespace Module\Game\Miner\Domain\Model;

use Domain\Model\Model;

/**
 * Class AreaModel
 * @package Module\Game\Miner\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $earnings
 * @property integer $price
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class AreaModel extends Model {
    public static function tableName() {
        return 'game_miner_area';
    }

    protected function rules() {
        return [
            'name' => 'string:0,255',
            'earnings' => 'required|int:0,9999',
            'price' => 'int',
            'amount' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'earnings' => 'Earnings',
            'price' => 'Price',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}