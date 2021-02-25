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
            $table->id();
            $table->column('name')->varchar(100);
            $table->uint('file_id')->default(0);
            $table->uint('user_id');
            $table->uint('left_id')->default(0);
            $table->uint('right_id')->default(0);
            $table->uint('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(FileModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('extension')->varchar(20);
            $table->column('md5')->varchar(32);
            $table->column('location')->varchar('200');
            $table->column('thumb')->varchar('200')->default('')
                ->comment('预览图');
            $table->uint('size')->default(0);
            $table->timestamps();
        })->append(ShareModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('mode')->tinyint(2)->default(ShareModel::SHARE_PUBLIC);
            $table->column('password')->varchar(20)->default('');
            $table->uint('user_id');
            $table->timestamp('death_at');
            $table->uint('view_count')->default(0);
            $table->uint('down_count')->default(0);
            $table->uint('save_count')->default(0);
            $table->timestamps();
        })->append(ShareFileModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('disk_id');
            $table->uint('share_id');
        })->append(ShareUserModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('share_id');
        })->autoUp();
    }
}