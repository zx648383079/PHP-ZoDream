<?php
namespace Module\CMS\Domain\Migrations;

use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateCmsTables extends Migration {

    public function up() {

        Schema::createTable(LinkageModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->notNull();
            $table->set('code')->char(20)->unique()->notNull();
        });
    }

    public function down() {

    }
}