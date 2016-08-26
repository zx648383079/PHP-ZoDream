<?php
namespace Domain\Model;

use Zodream\Domain\Access\Auth;
use \Zodream\Domain\Model\UserModel as BaseModel;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Database\Query;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Security\Hash;
use Zodream\Infrastructure\Request;

/**
* Class UserModel
* @property integer $id
* @property string $name
* @property string $email
* @property string $password
* @property string $sex
* @property string $avatar
* @property string $token
* @property integer $login_num
* @property string $update_ip
* @property integer $update_at
* @property string $previous_ip
* @property integer $previous_at
* @property string $create_ip
* @property integer $create_at
*/
class UserModel extends BaseModel {
	public static $table = 'user';

	protected $primaryKey = array (
	  	'id',
		'name',
	  	'email',
	);
	
	public $rememberMe = false;
	
	public $code = false;
	
	public $agree = false;
	
	public $rePassword = false;
	
	public $roles = [];
	
	public $oldPassword = false;

	protected function rules() {
		return array (
			'name' => 'required|string:3-30',
			'email' => '|string:3-100',
			'password' => '|string:3-64',
			'sex' => 'enum:男,女',
			'avatar' => '|string:3-200',
			'token' => '|string:3-60',
			'login_num' => '|int',
			'update_ip' => '|string:3-20',
			'update_at' => '|int',
			'previous_ip' => '|string:3-20',
			'previous_at' => '|int',
			'create_ip' => '|string:3-20',
			'create_at' => '|int',
		);
	}
	
	public function signInRules() {
		return [
			'email' => 'required|email',
			'password' => 'required|string:3-30',
			'code' => 'validateCode'
		];
	}
	
	public function signUpRules() {
		return [
			'name' => 'required|string:2-20',
			'email' => 'required|email',
			'password' => 'required|string:3-30',
			'rePassword' => 'validateRePassword',
			'agree' => ['required', 'message' => '必须同意相关协议！']
		];
	}

	public function resetRules() {
		return [
			'oldPassword'     => 'required|string:3-30',
			'password' => 'required|string:3-30',
			'rePassword' => 'validateRePassword',
		];
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'email' => 'Email',
		  'password' => 'Password',
		  'sex' => 'Sex',
		  'avatar' => 'Avatar',
		  'token' => 'Token',
		  'login_num' => 'Login Num',
		  'update_ip' => 'Update Ip',
		  'update_at' => 'Update At',
		  'previous_ip' => 'Previous Ip',
		  'previous_at' => 'Previous At',
		  'create_ip' => 'Create Ip',
		  'create_at' => 'Create At',
		);
	}

	public function setPassword($password) {
		$this->password = Hash::make($password);
	}

	public function validatePassword($password) {
		return Hash::verify($password, $this->password);
	}

	public static function findIdentity($id) {
		return static::findOne(['id' => $id]);
	}

	public static function findByName($name) {
		return static::findOne(['name' => $name]);
	}

	public static function findByEmail($email) {
		return static::findOne(['email' => $email]);
	}

	public static function findByOpenId($openId, $type = 'qq') {
		$user_id = (new Query())
			->from('user_oauth')
			->select('user_id')
			->where(['openid' => $openId, 'type' => $type])
			->scalar();
		if ($user_id === false) {
			return false;
		}
		return static::findOne($user_id);
	}

	public function validateCode() {
		if ($this->code === false) {
			return true;
		}
		$code = Factory::session()->get('code');
		if (empty($code) || $this->code != $code) {
			$this->setError('code', '验证码错误！');
			return false;
		}
		return true;
	}
	
	public function validateRePassword() {
		if ($this->rePassword === false) {
			return true;
		}
		if (empty($this->rePassword) || $this->rePassword != $this->password) {
			$this->setError('rePassword', '两次密码不一致！');
			return false;
		}
		return true;
	}
	
	public function signIn() {
		if (!$this->validate($this->signInRules())) {
			return false;
		}
		$user = $this->findByEmail($this->email);
		if (empty($user)) {
			$this->setError('email', '邮箱未注册！');
			return false;
		}
		if (!$user->validatePassword($this->password)) {
			$this->setError('password', '密码错误！');
			return false;
		}
		$user->previous_ip = $user->update_ip;
		$user->previous_at = $user->update_at;
		$user->login_num = intval($user->login_num) + 1;
		$user->update_ip = Request::ip();
		$user->update_at = time();
		if (!empty($this->rememberMe)) {
			$token = StringExpand::random(10);
			$user->token = $token;
			Cookie::set('token', $token, 3600 * 24 * 30);
		}
		if (!$user->save()) {
			return false;
		}
		/*$user->roles = EmpireModel::query('role_user r')->findAll(array(
			'right' => array(
				'authorization_role ar',
				'r.role_id = ar.role_id'
			),
			'left' => array(
				'authorization a',
				'a.id = ar.authorization_id'
			),
			'where' => 'r.user_id = '.$user['id']
		), array(
			'id' => 'a.id',
			'name' => 'a.name'
		));*/
		return $this->login($user);
	}
	
	public function signInOAuth() {
		
	}
	
	public function signUp() {
		if (!$this->validate($this->signUpRules())) {
			return false;
		}
		$this->setPassword($this->password);
		$this->create_at = time();
		$this->avatar = '/assets/images/avatar/'.random_int(0, 48).'.png';
		$this->create_ip = Request::ip();
		if (!$this->save()) {
			return false;
		}
		return $this->login($this);
	}

	public function resetPassword() {
		if (!$this->validate($this->resetRules())) {
			return false;
		}
		/** @var $user static */
		$user = Auth::user();
		if (!$user->validatePassword($this->password)) {
			return false;
		}
		$user->setPassword($this->password);
		return $user->save();
	}
}