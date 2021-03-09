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
            $table->string('name', 100);
            $table->uint('file_id')->default(0);
            $table->uint('user_id');
            $table->uint('left_id')->default(0);
            $table->uint('right_id')->default(0);
            $table->uint('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(FileModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('extension', 20);
            $table->char('md5', 32);
            $table->string('location', 200);
            $table->string('thumb', 200)->default('')
                ->comment('预览图');
            $table->uint('size')->default(0);
            $table->timestamps();
        })->append(ShareModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->uint('mode', 2)->default(ShareModel::SHARE_PUBLIC);
            $table->string('password', 20)->default('');
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