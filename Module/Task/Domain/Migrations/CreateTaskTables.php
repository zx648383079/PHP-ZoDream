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
            $table->comment('任务系统');
            $table->id();
            $table->uint('user_id');
            $table->uint('parent_id')->default(0);
            $table->column('name')->varchar(100);
            $table->column('description')->varchar()->default('');
            $table->uint('status', 2)->default(TaskModel::STATUS_NONE);
            $table->uint('every_time', 4)->default(0)->comment('每次计划时间');
            $table->uint('space_time', 2)->default(0)->comment('每次休息时间');
            $table->timestamp('start_at')->comment('任务开始时间');
            $table->uint('duration', 2)->default(0)->comment('每天连续次数');
            $table->uint('time_length')->default(0)->comment('总时间');
            $table->timestamps();
        })->append(TaskLogModel::tableName(), function (Table $table) {
            $table->comment('任务记录系统');
            $table->id();
            $table->uint('user_id');
            $table->uint('task_id');
            $table->uint('child_id')->default(0);
            $table->uint('day_id')->default(0);
            $table->uint('status', 2)->default(TaskLogModel::STATUS_NONE);
            $table->uint('outage_time', 5)->default(0)
                ->comment('打扰时间');
            $table->timestamp('end_at');
            $table->timestamp('created_at');
        })->append(TaskDayModel::tableName(), function (Table $table) {
            $table->comment('每日代办任务');
            $table->id();
            $table->uint('user_id');
            $table->uint('task_id');
            $table->column('today')->date();
            $table->uint('amount', 2)->default(1)->comment('剩余次数');
            $table->uint('success_amount', 2)->default(0)->comment('成功次数');
            $table->uint('pause_amount', 2)->default(0)->comment('暂停次数');
            $table->uint('failure_amount', 2)->default(0)->comment('中断次数');
            $table->uint('status', 2)->default(TaskDayModel::STATUS_NONE);
            $table->timestamps();
        })->append(TaskCommentModel::tableName(), function (Table $table) {
            $table->comment('任务执行评论');
            $table->id();
            $table->uint('user_id');
            $table->uint('task_id');
            $table->uint('log_id')->default(0)->comment('关联执行记录');
            $table->column('content')->varchar();
            $table->uint('type', 2)->default(0);
            $table->uint('status', 2)->default(TaskDayModel::STATUS_NONE);
            $table->timestamps();
        })->append(TaskShareModel::tableName(), function (Table $table) {
            $table->comment('任务分享');
            $table->id();
            $table->uint('user_id');
            $table->uint('task_id');
            $table->uint('share_type', 1)->default(0);
            $table->column('share_rule')->varchar(20)->default('');
            $table->timestamps();
        })->append(TaskShareUserModel::tableName(), function (Table $table) {
            $table->comment('任务分享领取用户');
            $table->id();
            $table->uint('user_id');
            $table->uint('share_id');
            $table->softDeletes();
            $table->timestamp('created_at');
        })->autoUp();
    }
}
