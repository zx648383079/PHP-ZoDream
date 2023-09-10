<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * Class OAuthModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property integer $user_id
 * @property integer $platform_id
 * @property string $nickname
 * @property string $vendor
 * @property string $identity
 * @property string $unionid
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

    public static function tableName(): string {
        return 'user_oauth';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'nickname' => 'string:0,30',
            'vendor' => 'string:0,30',
            'identity' => 'string:0,100',
            'unionid' => 'string:0,100',
            'data' => '',
            'created_at' => 'int',
            'platform_id' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'nickname' => '昵称',
            'vendor' => '平台',
            'identity' => 'Identity',
            'unionid' => '联合id',
            'data' => 'Data',
            'created_at' => 'Created At',
            'platform_id' => 'Platform Id',
        ];
    }


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    /**
     * 绑定用户
     * @param UserModel $user
     * @param string $identifier
     * @param string $unionId
     * @param string $type
     * @param string $nickname
     * @param int $platform_id
     * @return OAuthModel
     */
    public static function bindUser(UserModel $user,
                                    $identifier, $unionId = '',
                                    $type = self::TYPE_QQ, $nickname = '', $platform_id = 0) {
        return static::create([
            'user_id' => $user->id,
            'vendor' => $type,
            'identity' => $identifier.'',
            'unionid' => $unionId.'',
            'nickname' => $nickname,
            'platform_id' => $platform_id
        ]);
    }

    public static function findUserId($identifier,
                                      $type = self::TYPE_QQ) {
        return (int)static::where('vendor', $type)
            ->where('identity', $identifier)->value('user_id');
    }

    /**
     * 获取其他地方用到的openid
     * @param int $user_id
     * @param string $type
     * @param int $platform_id
     * @return mixed
     */
    public static function findOpenid($user_id, $type = self::TYPE_WX, $platform_id = 0) {
        return static::where('user_id', $user_id)
            ->where('platform_id', $platform_id)
            ->where('vendor', $type)->value('identity');
    }

    /**
     * 根据第三方授权令牌登录，不支持联合id查询
     * @param string $identifier
     * @param string $type
     * @return bool|UserModel
     * @throws \Exception
     */
    public static function findUser($identifier,
                                    $type = self::TYPE_QQ) {
        $user_id = static::findUserId($identifier, $type);
        if ($user_id < 1) {
            return null;
        }
        return UserModel::find($user_id);
    }

    /**
     * 根据第三方授权令牌或联合令牌登录
     * @param string $openid
     * @param string $unionId
     * @param string $type
     * @param int $platform_id
     * @return UserModel|null
     * @throws \Exception
     */
    public static function findUserWithUnion($openid,
                                            $unionId,
                                            $type = self::TYPE_QQ, $platform_id = 0) {
        /** @var OAuthModel $model */
        $model = static::where('vendor', $type)
            ->where('identity', $openid)->first();
        if (!empty($model) && $model->user_id < 1) {
            $model->delete();
            $model = null;
        }
        if (!empty($model)) {
            if (!empty($unionId)) {
                $model->unionid = $unionId;
                $model->save();
            }
            // 这里可以验证用户是否存在，不存在删除记录
            return UserModel::find($model->user_id);
        }
        $model = static::findWithUnion($unionId, $type, $platform_id);
        if (empty($model)) {
            return null;
        }
        if ($model->user_id < 1) {
            $model->delete();
            return null;
        }
        $user = UserModel::find($model->user_id);
        // 这里可以验证用户是否存在，不存在删除所有此用户记录
        self::bindUser($user, $openid, $unionId, $type, $model->nickname, $platform_id);
        return $user;
    }

    /**
     * @param string $unionId
     * @param string $type
     * @param int $platform_id
     * @return static
     */
    public static function findWithUnion($unionId,
                                         $type = self::TYPE_QQ, $platform_id = 0) {
        if (empty($unionId)) {
            return null;
        }
        $types = [$type];
        if ($type === self::TYPE_WX || $type === self::TYPE_WX_MINI) {
            $types = [self::TYPE_WX, self::TYPE_WX_MINI];
        }
        return static::whereIn('vendor', $types)
            ->where('unionid', $unionId)->first();
    }
}