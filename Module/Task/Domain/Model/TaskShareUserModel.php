<?php
namespace Module\Task\Domain\Model;

use Module\Task\Domain\Entities\TaskShareUserEntity;

/**
 * Class TaskShareUserModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $share_id
 * @property integer $created_at
 */
class TaskShareUserModel extends TaskShareUserEntity {
}