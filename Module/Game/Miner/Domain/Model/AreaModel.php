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
    public static function tableName(): string {
        return 'game_miner_area';
    }

    protected function rules(): array {
        return [
            'name' => 'string:0,255',
            'earnings' => 'required|int:0,9999',
            'price' => 'int',
            'amount' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '矿场名',
            'earnings' => '基本收益(/m)',
            'price' => '单次使用价格',
            'amount' => '总产量',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}