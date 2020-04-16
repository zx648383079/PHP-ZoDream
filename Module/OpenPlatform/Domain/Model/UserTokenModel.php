<?php
namespace Module\OpenPlatform\Domain\Model;


use Domain\Model\Model;

/**
 * Class UserTokenModel
 * @package Module\OpenPlatform\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $platform_id
 * @property string $token
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserTokenModel extends Model {
    public static function tableName() {
        return 'open_user_token';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'platform_id' => 'required|int',
            'token' => 'required|string',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'platform_id' => 'Platform Id',
            'token' => 'Token',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}