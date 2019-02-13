<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 消费渠道
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $user_id
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
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '渠道名',
            'user_id' => 'User Id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

}