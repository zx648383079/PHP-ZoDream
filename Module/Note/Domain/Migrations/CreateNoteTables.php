<?php
namespace Module\Note\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;
use Module\Note\Domain\Model\NoteModel;

class CreateNoteTables extends Migration {

    public function up() {
        Schema::createTable(NoteModel::tableName(), function(Table $table) {
            $table->setComment('便签');
            $table->set('id')->pk()->ai();
            $table->set('content')->varchar(255)->notNull()->comment('内容');
            $table->set('user_id')->int(10)->notNull();
            $table->timestamp('created_at');
        });
    }

    public function down() {
        Schema::dropTable(NoteModel::tableName());
    }
}