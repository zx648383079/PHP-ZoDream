<?php
namespace Module\Auth\Domain\Model;


use Module\Auth\Domain\Entities\Concerns\UserTrait;
use Module\Auth\Domain\Model\Concerns\FindPasswordTrait;
use Module\Auth\Domain\Model\Concerns\LoginTrait;
use Module\Auth\Domain\Model\Concerns\PasswordTrait;
use Module\Auth\Domain\Model\Concerns\RegisterTrait;
use Module\Auth\Domain\Model\Concerns\UserRoleTrait;
use Zodream\Database\Model\UserModel as BaseModel;
use Zodream\Helpers\Security\Hash;

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
 * @property integer $money
 * @property string $token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserModel extends BaseModel {

    use UserTrait, LoginTrait, RegisterTrait, PasswordTrait, UserRoleTrait, FindPasswordTrait;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SEX_MALE = 1; // 性别男
    const SEX_FEMALE = 2; //性别女

    public $sex_list = [
        '未知',
        '男',
        '女'
    ];

    protected $hidden = ['password'];
	
	public $rememberMe = false;
	
	public $code = false;
	
	public $agree = false;
	
	public $rePassword = false;
	
	public $roles = [];
	
	public $oldPassword = false;


    public function getSexLabelAttribute() {
	    return $this->sex_list[$this->sex];
    }

    public function getAvatarAttribute() {
        $avatar = $this->getAttributeSource('avatar');
        if (empty($avatar)) {
            return null;
        }
	    return url()->asset($avatar);
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

    /**
     * 更改为手动记录状态
     * @param bool $status
     * @param string $model
     * @return LoginLogModel
     * @throws \Exception
     */
    public function logLogin($status = true, $model = LoginLogModel::MODE_WEB) {
        return LoginLogModel::addLoginLog($this->email, $this->id, $status, $model);
    }

    public static function validateEmail($email) {
	    return !empty($email) && self::where('email', $email)->count() < 1;
    }

    /**
     * @param $id
     * @return UserModel|boolean
     * @throws \Exception
     */
	public static function findIdentity($id) {
		return static::where('id', $id)->where('status', self::STATUS_ACTIVE)->first();
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
		return static::where('name', $name)->where('status', self::STATUS_ACTIVE)->first();
	}

    /**
     * @param $email
     * @return UserModel|boolean
     * @throws \Exception
     */
	public static function findByEmail($email) {
		return static::where('email', $email)->where('status', self::STATUS_ACTIVE)->first();
	}

}