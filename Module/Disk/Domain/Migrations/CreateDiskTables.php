<?php
namespace Module\Disk\Domain\Migrations;

use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateDiskTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(DiskModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('file_id')->int(10)->defaultVal(0);
            $table->set('user_id')->int(10);
            $table->set('left_id')->int(10)->defaultVal(0);
            $table->set('right_id')->int(10)->defaultVal(0);
            $table->set('parent_id')->int(10)->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(FileModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('extension')->varchar(20);
            $table->set('md5')->varchar(32)->notNull();
            $table->set('location')->varchar('200')->notNull();
            $table->set('thumb')->varchar('200')->defaultVal('')
                ->comment('预览图');
            $table->set('size')->int(10)->defaultVal(0);
            $table->timestamps();
        })->append(ShareModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('mode')->tinyint(2)->defaultVal(ShareModel::SHARE_PUBLIC);
            $table->set('password')->varchar(20)->defaultVal('');
            $table->set('user_id')->int(10)->notNull();
            $table->timestamp('death_at');
            $table->set('view_count')->int(10)->defaultVal(0);
            $table->set('down_count')->int(10)->defaultVal(0);
            $table->set('save_count')->int(10)->defaultVal(0);
            $table->timestamps();
        })->append(ShareFileModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('disk_id')->int(10)->notNull();
            $table->set('share_id')->int(10)->notNull();
        })->append(ShareUserModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int(10)->notNull();
            $table->set('share_id')->int(10)->notNull();
        })->autoUp();
    }
}