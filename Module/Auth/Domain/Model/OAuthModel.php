<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * Class OAuthModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property integer $user_id
 * @property string $vendor
 * @property string $identity
 * @property string $data
 * @property integer $created_at
 */
class OAuthModel extends Model {
    public static function tableName() {
        return 'user_oauth';
    }

    /**
     * @return \Zodream\Database\Model\Relations\Relation
     */
    public function getUser() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }
}