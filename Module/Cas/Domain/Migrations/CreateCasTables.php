<?php
namespace Module\Cas\Domain\Migrations;

use Module\Cas\Domain\Model\ClientTicketModel;
use Module\Cas\Domain\Model\PGTicketModel;
use Module\Cas\Domain\Model\ServiceModel;
use Module\Cas\Domain\Model\TicketModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCasTables extends Migration {

    public function up() {
        $this->append(ServiceModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(60);
            $table->column('host')->varchar(200);
            $table->column('allow_proxy')->bool()->default(0);
            $table->column('enabled')->bool()->default(1);
            $table->timestamps();
        })->append(TicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('ticket')->varchar(60);
            $table->column('service_url')->varchar(200);
            $table->uint('service_id');
            $table->uint('user_id');
            $table->column('proxies')->text()->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(PGTicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('ticket')->varchar(60);
            $table->column('pgt_url')->varchar(200);
            $table->uint('service_id');
            $table->uint('user_id');
            $table->column('proxies')->text()->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(ClientTicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('ticket')->varchar(60);
            $table->column('session_id')->varchar(60);
            $table->timestamps();
        })->autoUp();
    }
}