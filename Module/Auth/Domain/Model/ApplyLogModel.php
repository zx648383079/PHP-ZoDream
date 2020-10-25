<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class ApplyLogModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $money
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ApplyLogModel extends Model {

	public static function tableName() {
        return 'user_apply_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'type' => 'int:0,127',
            'money' => 'int',
            'remark' => 'string:0,255',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'money' => 'Money',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}