<?php
declare(strict_types=1);
namespace Module\Cas\Domain\Migrations;

use Module\Cas\Domain\Model\ClientTicketModel;
use Module\Cas\Domain\Model\PGTicketModel;
use Module\Cas\Domain\Model\ServiceModel;
use Module\Cas\Domain\Model\TicketModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCasTables extends Migration {

    public function up(): void {
        $this->append(ServiceModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('host', 200);
            $table->bool('allow_proxy')->default(0);
            $table->bool('enabled')->default(1);
            $table->timestamps();
        })->append(TicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('ticket', 60);
            $table->string('service_url', 200);
            $table->uint('service_id');
            $table->uint('user_id');
            $table->text('proxies')->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(PGTicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('ticket', 60);
            $table->string('pgt_url', 200);
            $table->uint('service_id');
            $table->uint('user_id');
            $table->text('proxies')->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(ClientTicketModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('ticket', 60);
            $table->string('session_id', 60);
            $table->timestamps();
        })->autoUp();
    }
}