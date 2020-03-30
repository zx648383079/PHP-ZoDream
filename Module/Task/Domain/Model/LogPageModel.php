<?php
namespace Module\Task\Domain\Model;

/**
 * Class TaskLogModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $day_id
 * @property integer $status
 * @property integer $end_at
 * @property integer $outage_time
 * @property integer $time
 * @property integer $created_at
 */
class LogPageModel extends TaskLogModel {

    protected $append = ['time', 'task'];

}