<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class LoginLogModel
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property string $user
 * @property integer $status
 * @property string $mode
 * @property integer $created_at
 */
class LoginLogModel extends Model {

    const MODE_WEB = 'web';
    const MODE_APP = 'app';     // APP登陆
    const MODE_QR = 'qr';     // 扫描登陆
    const MODE_OAUTH = 'oauth';  //第三方登陆

	public static function tableName() {
        return 'user_login_log';
    }

    protected function rules() {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'int',
            'user' => 'string:0,100',
            'status' => 'int:0-9',
            'mode' => 'string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => '用户',
            'user' => '登录账户',
            'status' => '状态',
            'mode' => '登陆方式',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 纪录登录记录
     * @param string $user 登录邮箱
     * @param int $user_id
     * @param bool $status 成功或失败
     * @param string $mode 页面登录或其他
     * @return LoginLogModel
     * @throws \Exception
     */
	public static function addLoginLog($user, $user_id = 0, $status = false, $mode = self::MODE_WEB) {
		return static::create([
            'ip' => app('request')->ip(),
            'user' => $user,
            'user_id' => intval($user_id),
            'status' => $status ? 1 : 0,
            'mode' => $mode,
            'created_at' => time()
        ]);
	}
}