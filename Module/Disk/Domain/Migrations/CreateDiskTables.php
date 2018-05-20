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
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('file_id')->int(10)->defaultVal(0);
            $table->set('user_id')->int(10);
            $table->set('left_id')->int(10)->defaultVal(0);
            $table->set('right_id')->int(10)->defaultVal(0);
            $table->set('parent_id')->int(10)->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(FileModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('extension')->varchar(20);
            $table->set('md5')->varchar(32)->notNull();
            $table->set('location')->varchar('200')->notNull();
            $table->set('size')->int(10)->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(ShareModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('mode')->tinyint(2)->defaultVal(ShareModel::SHARE_PUBLIC);
            $table->set('password')->varchar(20);
            $table->set('user_id')->int(10);
            $table->set('death_at')->int(10)->defaultVal(0);
            $table->set('view_count')->int(10)->defaultVal(0);
            $table->set('down_count')->int(10)->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(ShareFileModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('disk_id')->int(10)->notNull();
            $table->set('share_id')->int(10)->notNull();
        });
        Schema::createTable(ShareUserModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int(10)->notNull();
            $table->set('share_id')->int(10)->notNull();
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