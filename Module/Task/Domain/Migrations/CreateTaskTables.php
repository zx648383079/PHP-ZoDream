<?php
namespace Module\Task\Domain\Migrations;

use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateTaskTables extends Migration {

    public function up() {
        Schema::createTable(TaskModel::tableName(), function (Table $table) {
            $table->setComment('任务系统');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull();
            $table->set('description')->varchar()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('time_length')->varchar()->notNull();
            $table->timestamps();
        });
        Schema::createTable(TaskLogModel::tableName(), function (Table $table) {
            $table->setComment('任务记录系统');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('task_id')->int()->notNull();
            $table->timestamp('end_at');
            $table->timestamp('created_at');
        });
    }
}
