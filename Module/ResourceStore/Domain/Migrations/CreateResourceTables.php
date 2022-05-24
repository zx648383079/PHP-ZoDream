<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\ResourceStore\Domain\Models\ResourceFileModel;
use Module\ResourceStore\Domain\Models\ResourceModel;
use Module\ResourceStore\Domain\Models\CategoryModel;
use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateResourceTables extends Migration {

    public function up() {
        ResourceRepository::comment()->migration($this);
        ResourceRepository::tag()->migration($this);
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->uint('parent_id')->default(0);
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('thumb')->default('');
        })->append(ResourceModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->text('content');
            $table->string('size', 20)->default('0');
            $table->uint('user_id');
            $table->uint('type', 2)->default(0)->comment('资源类型');
            $table->uint('cat_id');
            $table->uint('price')->default(0)->comment('价格');
            $table->bool('is_commercial')->default(0)->comment('是否允许商用');
            $table->bool('is_reprint')->default(0)->comment('是否允许转载');
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('download_count')->default(0);
            $table->timestamps();
        })->append(ResourceFileModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('res_id');
            $table->uint('file_type')->default(0)->comment('本地文件/网盘/种子');
            $table->string('file');
            $table->uint('click_count')->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'res_manage' => '资源商店管理'
        ]);
    }
}