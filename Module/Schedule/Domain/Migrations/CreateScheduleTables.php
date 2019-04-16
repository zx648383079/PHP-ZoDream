<?php
namespace Module\Schedule\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateScheduleTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::createTable('jobs', function(Table $table) {
            $table->setComment('计划任务列表');
            $table->set('id')->pk()->ai();
            $table->set('queue')->varchar()->index();
            $table->set('payload')->longtext();
            $table->set('attempts')->tinyint(2)->unsigned()->defaultVal(0);
            $table->timestamp('reserved_at');
            $table->timestamp('available_at');
            $table->timestamp('created_at');
        });
        Schema::createTable('failed_jobs', function(Table $table) {
            $table->setComment('失败任务');
            $table->set('id')->pk()->ai();
            $table->set('connection')->text();
            $table->set('queue')->text();
            $table->set('payload')->longtext();
            $table->set('exception')->longtext();
            $table->timestamp('failed_at');
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