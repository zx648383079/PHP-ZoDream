<?php
namespace Module\Disk\Domain\Migrations;

use Module\Disk\Domain\Model\ClientFileModel;
use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ServerFileModel;
use Module\Disk\Domain\Model\ServerModel;
use Module\Disk\Domain\Model\ShareFileModel;
use Module\Disk\Domain\Model\ShareModel;
use Module\Disk\Domain\Model\ShareUserModel;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateDiskTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (DiskRepository::useDistributed()) {
            $this->append(ServerModel::tableName(), function(Table $table) {
                $table->id();
                $table->string('token');
                $table->string('ip', 120);
                $table->string('port', 6);
                $table->bool('can_upload');
                $table->string('upload_url');
                $table->string('download_url');
                $table->string('ping_url');
                $table->uint('file_count');
                $table->uint('status', 1)->default(0);
                $table->timestamps();
            })->append(ServerFileModel::tableName(), function(Table $table) {
                $table->uint('server_id');
                $table->uint('file_id');
            })->append(ClientFileModel::tableName(), function(Table $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('extension', 20);
                $table->char('md5', 32);
                $table->string('location', 200);
                $table->string('size', 50)->default(0);
                $table->timestamps();
            });
        }
        $this->append(DiskModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('extension', 20);
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
            $table->string('size', 50)->default('0');
            $table->softDeletes();
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