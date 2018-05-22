<?php
namespace Module\Cas\Domain\Migrations;

use Module\Cas\Domain\Model\ClientTicketModel;
use Module\Cas\Domain\Model\PGTicketModel;
use Module\Cas\Domain\Model\ServiceModel;
use Module\Cas\Domain\Model\TicketModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateCasTables extends Migration {

    public function up() {
        Schema::createTable(ServiceModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(60)->notNull();
            $table->set('host')->varchar(200)->notNull();
            $table->set('allow_proxy')->bool()->defaultVal(0);
            $table->set('enabled')->bool()->defaultVal(1);
            $table->timestamps();
        });
        Schema::createTable(TicketModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ticket')->varchar(60)->notNull();
            $table->set('service_url')->varchar(200)->notNull();
            $table->set('service_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('proxies')->text();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
        Schema::createTable(PGTicketModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ticket')->varchar(60)->notNull();
            $table->set('pgt_url')->varchar(200)->notNull();
            $table->set('service_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('proxies')->text();
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