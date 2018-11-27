<?php
namespace Module\Auth\Domain\Model\Scene;

use Module\Auth\Domain\Model\UserModel;

/**
 * 普通场景用户数据
 * @package Module\Auth\Domain\Model\Scene
 */
class User extends UserModel {

    protected $visible = ['id', 'name', 'email', 'avatar', 'sex', 'token'];
}