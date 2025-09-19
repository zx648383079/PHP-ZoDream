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
 * @property string $mobile
 * @property string $password
 * @property integer $sex
 * @property string $avatar
 * @property string $birthday
 * @property integer $money
 * @property integer $credits
 * @property integer $parent_id
 * @property string $token
 * @property integer $status
 * @property integer $activated_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class UserModel extends BaseModel {

    use UserTrait, UserRoleTrait;

    const STATUS_DELETED = 0; // 已删除
    const STATUS_FROZEN = 2; // 账户已冻结
    const STATUS_UN_CONFIRM = 9; // 邮箱注册，未确认邮箱
    const STATUS_ACTIVE = 10; // 账户正常
    const STATUS_ACTIVE_VERIFIED = 15; // 账户正常&实名认证了

    const SEX_MALE = 1; // 性别男
    const SEX_FEMALE = 2; //性别女

    protected array $hidden = ['password', 'token'];

    protected array $append = ['sex_label'];

	public $roles = [];

    public function getSexLabelAttribute() {
	    return __('sex.'.$this->sex);
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

	public function setPassword(string $password) {
		$this->password = Hash::make($password);
	}

	public function validatePassword(string $password) {
		return Hash::verify($password, $this->password);
	}

    /**
     * 更改为手动记录状态
     * @param bool $status
     * @param string $model
     * @return LoginLogModel
     * @throws \Exception
     */
    public function logLogin(bool $status = true, string $model = LoginLogModel::MODE_WEB) {
        return LoginLogModel::addLoginLog($this->email, $this->id, $status, $model);
    }

    public static function validateEmail(string $email): bool {
	    return !empty($email) && self::where('email', $email)->count() < 1;
    }

    public static function getRememberTokenName(): string {
        return 'token';
    }

    /**
     * @param int|string $id
     * @return UserModel|null
     */
	public static function findIdentity(int|string $id): ?static {
		return static::where('id', $id)->first();
	}

    /**
     *
     * @param string $username
     * @param string $password
     * @return null|UserModel
     * @throws \Exception
     */
	public static function findByAccount(string $username, string $password): ?static {
        $user = self::findByEmail($username);
        if (empty($user)) {
            return null;
        }
        if (!$user->validatePassword($password)) {
            return null;
        }
        return $user;
    }

    /**
     * @param string $name
     * @return UserModel|null
     */
	public static function findByName(string $name): ?static {
		return static::where('name', $name)->first();
	}

    /**
     * @param string $email
     * @return UserModel|null
     */
	public static function findByEmail(string $email): ?static {
		return static::where('email', $email)->first();
	}

}