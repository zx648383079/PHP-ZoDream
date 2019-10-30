<?php
namespace Module\Task\Domain\Migrations;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTaskTables extends Migration {

    public function up() {
        $this->append(TaskModel::tableName(), function (Table $table) {
            $table->setComment('任务系统');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('name')->varchar(100)->notNull();
            $table->set('description')->varchar()->defaultVal('');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('every_time')->int(1)->defaultVal(0)->comment('每次计划时间');
            $table->set('time_length')->int()->defaultVal(0)->comment('总时间');
            $table->timestamps();
        })->append(TaskLogModel::tableName(), function (Table $table) {
            $table->setComment('任务记录系统');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('day_id')->int()->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('outage_time')->smallInt(5)->defaultVal(0)
                ->comment('打扰时间');
            $table->timestamp('end_at');
            $table->timestamp('created_at');
        })->append(TaskDayModel::tableName(), function (Table $table) {
            $table->setComment('每日代办任务');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->set('today')->char(8)->notNull();
            $table->set('amount')->tinyint(1)->defaultVal(1)->comment('剩余次数');
            $table->set('success_amount')->tinyint(1)->defaultVal(0)->comment('成功次数');
            $table->set('pause_amount')->tinyint(1)->defaultVal(0)->comment('暂停次数');
            $table->set('failure_amount')->tinyint(1)->defaultVal(0)->comment('中断次数');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        });
        parent::up();
    }
}
