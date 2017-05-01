<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * Class OAuthModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $openid
 * @property string $data
 * @property integer $create_at
 */
class OAuthModel extends Model {
    public static function tableName() {
        return 'user_oauth';
    }

    /**
     * @return UserModel
     */
    public function getUser() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }
}