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
 * @property integer $is_self
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserTokenModel extends Model {
    public static function tableName(): string {
        return 'open_user_token';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'platform_id' => 'required|int',
            'token' => 'required|string',
            'is_self' => 'int',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'platform_id' => 'Platform Id',
            'token' => 'Token',
            'is_self' => 'Is Self',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function platform() {
        return $this->hasOne(PlatformSimpleModel::class, 'id', 'platform_id');
    }
}