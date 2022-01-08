<?php
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Entities\Concerns\UserTrait;

/**
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
 * @property integer $updated_at
 * @property integer $created_at
 */
class UserEntity extends Entity {

    use UserTrait;
}