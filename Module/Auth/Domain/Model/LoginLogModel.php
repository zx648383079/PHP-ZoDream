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
    const MODE_TICKET = 'ticket'; // 一次性凭证
    const MODE_APP = 'app';     // APP登陆
    const MODE_QR = 'qr';     // 扫描登陆
    const MODE_OAUTH = 'oauth';  //第三方登陆
    const MODE_WEBAUTHN = 'webauthn'; // passkey 登录

	public static function tableName(): string {
        return 'user_login_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'int',
            'user' => 'string:0,100',
            'status' => 'int:0,127',
            'mode' => 'string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
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
	public static function addLoginLog(string $user, int $user_id = 0, bool $status = false, string $mode = self::MODE_WEB) {
		return static::create([
            'ip' => request()->ip(),
            'user' => $user,
            'user_id' => intval($user_id),
            'status' => $status ? 1 : 0,
            'mode' => $mode,
            'created_at' => time()
        ]);
	}

    /**
     * 今天失败登录的次数
     * @param string $ip
     * @param integer $time
     * @return integer
     */
	public static function failureCount(string $ip, int $time) {
	    return self::where('ip', $ip)
                ->where('status', 0)
                ->where('created_at', '>=', $time)->count();
    }
}