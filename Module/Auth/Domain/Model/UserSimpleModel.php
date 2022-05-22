<?php
namespace Module\Auth\Domain\Model;


use Module\Auth\Domain\Entities\UserEntity;

/**
 * Class UserSimpleModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $avatar
 */
class UserSimpleModel extends UserEntity {

    const SIMPLE_MODE = ['id', 'name', 'avatar'];

    public function getAvatarAttribute() {
        $avatar = $this->getAttributeSource('avatar');
        if (empty($avatar)) {
            return null;
        }
        return url()->asset($avatar);
    }

    public static function converterFrom(UserEntity $user) {
        $data = new UserSimpleModel();
        foreach (self::SIMPLE_MODE as $key) {
            $data[$key] = $user[$key];
        }
        return $data;
    }

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }
}