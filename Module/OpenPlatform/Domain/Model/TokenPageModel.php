<?php
namespace Module\OpenPlatform\Domain\Model;


use Domain\Model\Model;

/**
 * Class UserTokenModel
 * @package Module\OpenPlatform\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $platform_id
 * @property string $token
 * @property integer $is_self
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class TokenPageModel extends UserTokenModel {

    protected $append = ['platform', 'status'];

    public function getTokenAttribute() {
        return $this->is_self > 0 ? $this->getAttributeSource('token') : '[不允许查看]';
    }

    public function getExpiredAtAttribute() {
        return $this->formatTimeAttribute('expired_at');
    }

    public function getStatusAttribute() {
        return $this->getAttributeSource('expired_at') < time() ? '已过期' : '正常';
    }
}