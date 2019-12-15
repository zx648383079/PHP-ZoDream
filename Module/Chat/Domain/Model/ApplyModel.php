<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class ApplyModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $remark
 * @property integer $apply_user
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ApplyModel extends Model {
    public static function tableName() {
        return 'chat_apply';
    }

    protected function rules() {
        return [
            'group_id' => 'required|int',
            'user_id' => 'required|int',
            'remark' => 'string:0,255',
            'apply_user' => 'required|int',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'remark' => 'Remark',
            'apply_user' => 'Apply User',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function applier() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'apply_user');
    }

    public static function canApply($user_id) {
        return static::where('apply_user', auth()->id())
            ->where('user_id', $user_id)
            ->where('status', '<', 2)->count() < 1;
    }


}