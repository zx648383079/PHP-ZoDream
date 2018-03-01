<?php
namespace Module\Auth\Domain\Model;

use Zodream\Infrastructure\Http\Request;
use Domain\Model\Model;

/**
 * Class LoginLogModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property string $ip
 * @property string $user
 * @property integer $status
 * @property string $mode
 * @property integer $created_at
 */
class LoginLogModel extends Model {

	public static function tableName() {
        return 'login_log';
    }

    protected function rules() {
		return array (
		  'ip' => 'required|string:0,120',
		  'user' => 'required|string:0,45',
		  'status' => 'required|bool',
		  'mode' => 'string:0,45',
		  'created_at' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'ip' => 'Ip',
		  'user' => 'User',
		  'status' => 'Status',
		  'mode' => 'Mode',
		  'created_at' => 'Create At',
		);
	}

	/**
	 * 纪录登录记录
	 * @param string $user 登录邮箱
	 * @param bool $status 成功或失败
	 * @param int $mode 页面登录或其他
	 * @return LoginLogModel
     */
	public static function addLoginLog($user, $status = false, $mode = 1) {
		return static::create([
            'ip' => Request::ip(),
            'user' => $user,
            'status' => $status,
            'mode' => $mode,
            'created_at' => time()
        ]);
	}
}