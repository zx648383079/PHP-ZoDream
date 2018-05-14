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

    const TYPE_QQ = 'qq';
    const TYPE_WX = 'wx';
    const TYPE_WEIBO = 'weibo';
    const TYPE_TAOBAO = 'taobao';
    const TYPE_ALIPAY = 'alipay';

    public static function tableName() {
        return 'user_oauth';
    }


    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * 绑定用户
     * @param User $user
     * @param $identifier
     * @param string $type
     * @return OAuthModel
     */
    public static function bindUser(User $user,
                                    $identifier,
                                    $type = self::TYPE_QQ) {
        return static::create([
            'user_id' => $user->id,
            'vendor' => $type,
            'identity' => $identifier
        ]);
    }

    /**
     * 根据第三方授权令牌登录
     * @param string $identifier
     * @param string $type
     * @return bool|UserModel
     */
    public static function findUser($identifier,
                                    $type = self::TYPE_QQ) {
        $model = static::where('vendor', $type)
            ->where('identity', $identifier)->one();
        if (empty($model)) {
            return false;
        }
        return $model->user;
    }

    /**
     * 根据第三方授权令牌或联合令牌登录
     * @param string $openid
     * @param string $unionid
     * @param string $type
     * @return UserModel|bool
     */
    public static function findOrUpdateUser($openid,
                                            $unionid,
                                            $type = self::TYPE_QQ) {
        $user = static::findUser($openid, $type);
        if (!empty($user)) {
            return $user;
        }
        return static::findUser($unionid, $type);
    }
}