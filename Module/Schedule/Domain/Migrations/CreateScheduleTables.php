<?php
namespace Module\Schedule\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;
use Zodream\Infrastructure\Queue\DatabaseQueue;
use Zodream\Infrastructure\Queue\Failed\DatabaseFailedJobProvider;

class CreateScheduleTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::createTable('jobs', function(Table $table) {
            $table->setComment('计划任务列表');
            DatabaseQueue::createMigration($table);
        });
        Schema::createTable('failed_jobs', function(Table $table) {
            $table->setComment('失败任务');
            DatabaseFailedJobProvider::createMigration($table);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable('jobs');
        Schema::dropTable('failed_jobs');
    }
}