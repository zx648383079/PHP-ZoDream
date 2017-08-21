<?php
namespace Module\Disk\Domain\Migrations;

use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateDiskTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(DiskModel::tableName(), function(Table $table) {

        });
        Schema::createTable(FileModel::tableName(), function(Table $table) {

        });
        Schema::createTable(ShareFileModel::tableName(), function(Table $table) {

        });
        Schema::createTable(ShareModel::tableName(), function(Table $table) {

        });
        Schema::createTable(ShareUserModel::tableName(), function(Table $table) {

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(DiskModel::tableName());
        Schema::dropTable(FileModel::tableName());
        Schema::dropTable(ShareFileModel::tableName());
        Schema::dropTable(ShareModel::tableName());
        Schema::dropTable(ShareUserModel::tableName());
    }
}