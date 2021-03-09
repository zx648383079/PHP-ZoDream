<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class ApplyModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $classify_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $apply_user_id
 */
class ApplyModel extends Model {
    public static function tableName() {
        return 'chat_apply';
    }

    protected function rules() {
        return [
            'remark' => 'string:0,255',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
            'classify_id' => 'int',
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'apply_user_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'classify_id' => 'Classify Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'apply_user_id' => 'Apply User Id',
        ];
    }


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function applier() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'apply_user_id');
    }

    public static function canApply($user_id) {
        return static::where('apply_user', auth()->id())
            ->where('user_id', $user_id)
            ->where('status', '<', 2)->count() < 1;
    }


}