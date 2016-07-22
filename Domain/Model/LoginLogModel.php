<?php
namespace Domain\Model;
use Zodream\Infrastructure\Request;

/**
* Class LoginLogModel
* @property integer $id
* @property string $ip
* @property string $user
* @property integer $status
* @property string $mode
* @property integer $create_at
*/
class LoginLogModel extends Model {
	public static $table = 'login_log';

	protected function rules() {
		return array (
		  'ip' => 'required|string:3-20',
		  'user' => 'required|string:3-45',
		  'status' => 'required|int:0-1',
		  'mode' => '|string:3-45',
		  'create_at' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'ip' => 'Ip',
		  'user' => 'User',
		  'status' => 'Status',
		  'mode' => 'Mode',
		  'create_at' => 'Create At',
		);
	}

	/**
	 * 纪录登录记录
	 * @param string $user 登录邮箱
	 * @param bool $status 成功或失败
	 * @param int $mode 页面登录或其他
	 * @return int
	 */
	public static function addLoginLog($user, $status = false, $mode = 1) {
		return (new static)->add(array(
			'ip' => Request::ip(),
			'user' => $user,
			'status' => $status,
			'mode' => $mode,
			'create_at' => time()
		));
	}
}