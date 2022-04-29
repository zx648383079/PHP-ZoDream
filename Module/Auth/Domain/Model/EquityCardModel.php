<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 *
 * 有期限的权益卡
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $status
 * @property integer $expired_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class EquityCardModel extends Model {

    public static function tableName() {
        return 'user_equity_card';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'name' => 'required|string:0,32',
            'status' => 'int:0,127',
            'expired_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'status' => 'Status',
            'expired_at' => 'Expired At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}