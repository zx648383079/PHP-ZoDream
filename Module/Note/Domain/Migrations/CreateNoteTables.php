<?php
namespace Module\Note\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Note\Domain\Model\NoteModel;

class CreateNoteTables extends Migration {

    public function up() {
        $this->append(NoteModel::tableName(), function(Table $table) {
            $table->comment('便签');
            $table->id();
            $table->string('content')->comment('内容');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->autoUp();
    }
}