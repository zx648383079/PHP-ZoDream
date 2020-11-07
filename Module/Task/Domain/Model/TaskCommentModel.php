<?php
namespace Module\Task\Domain\Model;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Task\Domain\Entities\TaskCommentEntity;

/**
 * Class TaskCommentModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $log_id
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskCommentModel extends TaskCommentEntity {


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}