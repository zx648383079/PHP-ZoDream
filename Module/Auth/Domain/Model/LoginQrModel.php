<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Str;
use Zodream\Http\Uri;

/**
 * Class LoginQrModel
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $status
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $url
 */
class LoginQrModel extends Model {

    const STATUS_UN_SCAN = 0;  //未扫码
    const STATUS_UN_CONFIRM = 1;  // 已扫码待确认
    const STATUS_SUCCESS = 2;     // 登录成功
    const STATUS_REJECT = 3;      // 拒绝登录

    public static function tableName() {
        return 'user_login_qr';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'token' => 'required|string:0,32',
            'status' => 'int:0,9',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'token' => 'Token',
            'status' => 'Status',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 是否过期
     * @return bool
     */
    public function isExpired() {
        return $this->expired_at < time();
    }

    public function getUrlAttribute() {
        return url('./qr/authorize', ['token' => $this->token], true, false);
    }

    public static function generateToken() {
        return md5(Str::randomBytes(20).time());
    }

    /**
     * @param $token
     * @return static
     */
    public static function findByToken($token) {
        return self::where('token', $token)->one();
    }

    public static function findIfToken($token) {
        if (strpos($token, 'token=') > 0) {
            $url = new Uri($token);
            $token = $url->getData('token');
        }
        return static::findByToken($token);
    }

    public static function createNew() {
        return self::create([
           'token' => self::generateToken(),
           'status' => 0,
           'expired_at' => time() + 300,
        ]);
    }
}