<?php
namespace Module\Task\Domain\Model;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Task\Domain\Entities\TaskShareEntity;

/**
 * Class TaskShareModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $share_type
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskShareModel extends TaskShareEntity {

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id')
            ->with('children');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getUrlAttribute() {
        return url('./share', ['id' => $this->id]);
    }
}