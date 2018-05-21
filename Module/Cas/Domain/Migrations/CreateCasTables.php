<?php
namespace Module\Cas\Domain\Migrations;

use Module\Cas\Domain\Model\ClientTicketModel;
use Module\Cas\Domain\Model\TicketModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateCasTables extends Migration {

    public function up() {
        Schema::createTable(TicketModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ticket')->varchar(60)->notNull();
            $table->set('service')->varchar(200)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
        Schema::createTable(ClientTicketModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ticket')->varchar(60)->notNull();
            $table->set('session_id')->varchar(60)->notNull();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(TicketModel::tableName());
        Schema::dropTable(ClientTicketModel::tableName());
    }
}