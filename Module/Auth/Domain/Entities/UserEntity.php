<?php
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Entities\Concerns\UserTrait;

/**
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
class UserEntity extends Entity {

    use UserTrait;
}