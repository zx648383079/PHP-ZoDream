<?php
namespace Module\Auth\Domain\Model;

use Zodream\Domain\Access\Auth;
use Zodream\Domain\Model\UserModel as BaseModel;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\Security\Hash;
use Zodream\Infrastructure\Http\Request;
/**
 * Class UserModel
 * @package Domain\Model\Auth
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $sex
 * @property string $avatar
 * @property string $token
 * @property integer $delete_at
 * @property integer $create_at
 */
class UserModel extends BaseModel {
	public static function tableName() {
        return 'user';
    }

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

	public function init() {
	    $this->on(static::AFTER_LOGIN, function() {
	        LoginLogModel::addLoginLog($this->name, true);
        });
    }

    protected function rules() {
		return array (
			'name' => 'required|string:3-30',
			'email' => 'string:3-100',
			'password' => 'string:3-64',
			'sex' => 'int',
			'avatar' => 'string:3-200',
			'token' => 'string:3-60',
			'delete_at' => 'int',
			'create_at' => 'int',
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
			'agree' => ['validateAgree', 'message' => '必须同意相关协议！']
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
		  'delete_at' => 'Previous At',
		  'create_at' => 'Create At',
		);
	}

	public function setPassword($password) {
		$this->password = Hash::make($password);
	}

	public function validatePassword($password) {
		return Hash::verify($password, $this->password);
	}

	public function validateAgree() {
	    return !empty($this->agree);
    }

    /**
     * @param $id
     * @return UserModel|boolean
     */
	public static function findIdentity($id) {
		return static::find($id);
	}

    /**
     * @param $name
     * @return UserModel|boolean
     */
	public static function findByName($name) {
		return static::find(['name' => $name]);
	}

    /**
     * @param $email
     * @return UserModel|boolean
     */
	public static function findByEmail($email) {
		return static::find(['email' => $email]);
	}

    /**
     * @param $openId
     * @param string $type
     * @return UserModel|boolean
     */
	public static function findByOpenId($openId, $type = 'qq') {
		$user_id = (new Query())
			->from('user_oauth')
			->select('user_id')
			->where(['openid' => $openId, 'type' => $type])
			->scalar();
		if ($user_id === false) {
			return false;
		}
		return static::findIdentity($user_id);
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
		if ($user->delete_at > 0) {
            $this->setError('delete_at', '此用户已被禁止登录！');
            return false;
        }
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
		return $user->login();
	}

	public function signInOAuth() {
		
	}

    /**
     * @return UserModel|boolean
     */
	public function signInHeader() {
        list($this->email, $this->password) = $this->getBasicAuthCredentials();
        return $this->signIn();
    }

    protected function getBasicAuthCredentials() {
        $header = Request::header('Authorization');
        if (empty($header)) {
            return [null, null];
        }
        if (is_array($header)) {
            $header = current($header);
        }
        if (strpos($header, 'Basic ') !== 0) {
            return [null, null];
        }
        if (!($decoded = base64_decode(substr($header, 6)))) {
            return [null, null];
        }
        if (strpos($decoded, ':') === false) {
            return [null, null]; // HTTP Basic header without colon isn't valid
        }
        return explode(':', $decoded, 2);
    }
	
	public function signUp() {
		if (!$this->validate($this->signUpRules())) {
			return false;
		}
        $user = $this->findByEmail($this->email);
        if (!empty($user)) {
            $this->setError('email', '邮箱已注册！');
            return false;
        }
		$this->setPassword($this->password);
		$this->create_at = time();
		$this->avatar = '/assets/images/avatar/'.StringExpand::randomInt(0, 48).'.png';
		$this->sex = 1;
		if (!$this->save()) {
			return false;
		}
		return $this->login();
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