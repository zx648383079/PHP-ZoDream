<?php
namespace Module\Auth\Domain\Model;


use Module\Auth\Domain\Model\Concerns\FindPasswordTrait;
use Module\Auth\Domain\Model\Concerns\LoginTrait;
use Module\Auth\Domain\Model\Concerns\PasswordTrait;
use Module\Auth\Domain\Model\Concerns\RegisterTrait;
use Module\Auth\Domain\Model\Concerns\UserRoleTrait;
use Zodream\Database\Model\UserModel as BaseModel;
use Zodream\Infrastructure\Security\Hash;

use Zodream\Service\Factory;

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
 * @property integer $deleted_at
 * @property integer $created_at
 */
class UserModel extends BaseModel {

    use LoginTrait, RegisterTrait, PasswordTrait, UserRoleTrait, FindPasswordTrait;

    const SEX_MALE = 1; // 性别男
    const SEX_FEMALE = 2; //性别女

    public $sex_list = [
        '未知',
        '男',
        '女'
    ];

	public static function tableName() {
        return 'user';
    }
	
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
			'name' => 'required|string:0,30',
			'email' => 'string:0,100',
			'password' => 'string:0,64',
			'sex' => 'int',
			'avatar' => 'string:0,200',
			'token' => 'string:0,60',
			'deleted_at' => 'int',
			'created_at' => 'int',
		);
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
		  'deleted_at' => 'Previous At',
		  'created_at' => 'Create At',
		);
	}

	public function getSexLabelAttribute() {
	    return $this->sex_list[$this->sex];
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
     * @throws \Exception
     */
	public static function findIdentity($id) {
		return static::find($id);
	}

    /**
     *
     * @param $username
     * @param $password
     * @return bool|UserModel|void|\Zodream\Infrastructure\Interfaces\UserObject
     * @throws \Exception
     */
	public static function findByAccount($username, $password) {
        $user = self::findByEmail($username);
        if (empty($user)) {
            return false;
        }
        if (!$user->validatePassword($password)) {
            return false;
        }
        return $user;
    }

    /**
     * @param $name
     * @return UserModel|boolean
     * @throws \Exception
     */
	public static function findByName($name) {
		return static::find(['name' => $name]);
	}

    /**
     * @param $email
     * @return UserModel|boolean
     * @throws \Exception
     */
	public static function findByEmail($email) {
		return static::find(['email' => $email]);
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


}