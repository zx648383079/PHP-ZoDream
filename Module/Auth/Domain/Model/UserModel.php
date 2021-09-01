<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Model;


use Module\Auth\Domain\Entities\Concerns\UserTrait;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\Concerns\UserRoleTrait;
use Zodream\Database\Model\UserModel as BaseModel;
use Zodream\Helpers\Security\Hash;

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
 * @property integer $parent_id
 * @property string $birthday
 * @property string $token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserModel extends BaseModel {

    use UserTrait, UserRoleTrait;

    const STATUS_DELETED = 0; // 已删除
    const STATUS_FROZEN = 2; // 账户已冻结
    const STATUS_ACTIVE = 10; // 账户正常

    const SEX_MALE = 1; // 性别男
    const SEX_FEMALE = 2; //性别女

    public $sex_list = [
        '未知',
        '男',
        '女'
    ];

    protected array $hidden = ['password', 'token', 'status'];

    protected array $append = ['sex_label'];

	public $roles = [];

    public function getSexLabelAttribute() {
	    return $this->sex_list[$this->sex] ?? $this->sex_list[0];
    }

    public function getAvatarAttribute() {
        $avatar = $this->getAttributeSource('avatar');
        if (empty($avatar)) {
            return null;
        }
	    return url()->asset($avatar);
    }

    public function getBulletinCountAttribute() {
        return BulletinUserModel::where('user_id', $this->id)->where('status', 0)->count();
    }

	public function setPassword($password) {
		$this->password = Hash::make($password);
	}

	public function validatePassword($password) {
		return Hash::verify($password, $this->password);
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

    public static function getRememberTokenName() {
        return 'token';
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
     * @return bool|UserModel
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