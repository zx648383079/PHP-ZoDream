<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class AdminLogModel
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property string $action
 * @property string $remark
 * @property integer $created_at
 */
class AdminLogModel extends Model {

	public static function tableName() {
        return 'user_admin_log';
    }

    protected function rules() {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'action' => 'required|string:0,30',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'action' => 'Action',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}