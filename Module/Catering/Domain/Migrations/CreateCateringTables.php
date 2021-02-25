<?php
namespace Module\Catering\Domain\Migrations;

use Module\Catering\Domain\Entities\AddressEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;


class CreateCateringTables extends Migration {

    public function up() {
        $this->append(AddressEntity::tableName(), function(Table $table) {
        })->autoUp();
    }

}