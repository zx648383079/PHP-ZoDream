<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * Class OAuthModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property string $vendor
 * @property string $identity
 * @property string $data
 * @property integer $created_at
 */
class OAuthModel extends Model {

    const TYPE_QQ = 'qq';
    const TYPE_WX = 'wx';
    const TYPE_WX_MINI = 'wx_mini'; // 微信小程序
    const TYPE_WEIBO = 'weibo';
    const TYPE_TAOBAO = 'taobao';
    const TYPE_ALIPAY = 'alipay';

    public static function tableName() {
        return 'user_oauth';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'nickname' => 'string:0,30',
            'vendor' => 'string:0,30',
            'identity' => 'string:0,100',
            'data' => '',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'nickname' => '昵称',
            'vendor' => '平台',
            'identity' => 'Identity',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }


    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * 绑定用户
     * @param UserModel $user
     * @param $identifier
     * @param string $type
     * @param string $nickname
     * @return OAuthModel
     */
    public static function bindUser(UserModel $user,
                                    $identifier,
                                    $type = self::TYPE_QQ, $nickname = '') {
        return static::create([
            'user_id' => $user->id,
            'vendor' => $type,
            'identity' => $identifier,
            'nickname' => $nickname
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
     * @param string $unionId
     * @param string $type
     * @return UserModel|bool
     */
    public static function findUserWithUnion($openid,
                                            $unionId,
                                            $type = self::TYPE_QQ) {
        $user = static::findUser($openid, $type);
        if (!empty($user)) {
            return $user;
        }
        return static::findUser($unionId, $type);
    }
}