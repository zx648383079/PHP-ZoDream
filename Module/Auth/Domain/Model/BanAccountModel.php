<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * 禁止注册登录的账户
 * @property integer $id
 * @property integer $user_id
 * @property string $item_key
 * @property integer $item_type
 * @property integer $platform_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class BanAccountModel extends Model {

    public static function tableName() {
        return 'user_ban_account';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'item_key' => 'required|string:0,100',
            'item_type' => 'int:0,127',
            'platform_id' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'item_key' => 'Item Key',
            'item_type' => 'Item Type',
            'platform_id' => 'Platform Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}