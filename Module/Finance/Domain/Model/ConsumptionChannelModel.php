<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 消费渠道
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */
class ConsumptionChannelModel extends Model {
    public static function tableName() {
        return 'finance_consumption_channel';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}