<?php
namespace Module\Task\Domain\Migrations;

use Module\Task\Domain\Model\TaskCommentModel;
use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Model\TaskShareModel;
use Module\Task\Domain\Model\TaskShareUserModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTaskTables extends Migration {

    public function up() {
        $this->append(TaskModel::tableName(), function (Table $table) {
            $table->setComment('任务系统');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('name')->varchar(100)->notNull();
            $table->set('description')->varchar()->defaultVal('');
            $table->set('status')->tinyint(1)->defaultVal(TaskModel::STATUS_NONE);
            $table->set('every_time')->smallInt(4)->defaultVal(0)->comment('每次计划时间');
            $table->set('space_time')->tinyint(1)->defaultVal(0)->comment('每次休息时间');
            $table->set('start_at')->int(10)->defaultVal(0)->comment('任务开始时间');
            $table->set('duration')->tinyint(1)->defaultVal(0)->comment('每天连续次数');
            $table->set('time_length')->int()->defaultVal(0)->comment('总时间');
            $table->timestamps();
        })->append(TaskLogModel::tableName(), function (Table $table) {
            $table->setComment('任务记录系统');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('child_id')->int()->defaultVal(0);
            $table->set('day_id')->int()->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(TaskLogModel::STATUS_NONE);
            $table->set('outage_time')->smallInt(5)->defaultVal(0)
                ->comment('打扰时间');
            $table->timestamp('end_at');
            $table->timestamp('created_at');
        })->append(TaskDayModel::tableName(), function (Table $table) {
            $table->setComment('每日代办任务');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('today')->date()->notNull();
            $table->set('amount')->tinyint(1)->defaultVal(1)->comment('剩余次数');
            $table->set('success_amount')->tinyint(1)->defaultVal(0)->comment('成功次数');
            $table->set('pause_amount')->tinyint(1)->defaultVal(0)->comment('暂停次数');
            $table->set('failure_amount')->tinyint(1)->defaultVal(0)->comment('中断次数');
            $table->set('status')->tinyint(1)->defaultVal(TaskDayModel::STATUS_NONE);
            $table->timestamps();
        })->append(TaskCommentModel::tableName(), function (Table $table) {
            $table->setComment('任务执行评论');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('log_id')->int()->defaultVal(0)->comment('关联执行记录');
            $table->set('content')->varchar()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(TaskDayModel::STATUS_NONE);
            $table->timestamps();
        })->append(TaskShareModel::tableName(), function (Table $table) {
            $table->setComment('任务分享');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('share_type')->tinyint(1)->defaultVal(0);
            $table->set('share_rule')->varchar(20)->defaultVal('');
            $table->timestamps();
        })->append(TaskShareUserModel::tableName(), function (Table $table) {
            $table->setComment('任务分享领取用户');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('share_id')->int()->notNull();
            $table->softDeletes();
            $table->timestamp('created_at');
        })->autoUp();
    }
}
