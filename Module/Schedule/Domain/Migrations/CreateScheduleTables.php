<?php
namespace Module\Schedule\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
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
        $this->append('jobs', function(Table $table) {
            $table->comment('计划任务列表');
            DatabaseQueue::createMigration($table);
        })->append('failed_jobs', function(Table $table) {
            $table->comment('失败任务');
            DatabaseFailedJobProvider::createMigration($table);
        })->autoUp();

    }
}