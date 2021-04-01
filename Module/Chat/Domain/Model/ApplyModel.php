<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class ApplyModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property string $remark
 * @property integer $user_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class ApplyModel extends Model {
    public static function tableName() {
        return 'chat_apply';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'remark' => 'string:0,255',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'remark' => 'Remark',
            'user_id' => 'User Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }


}