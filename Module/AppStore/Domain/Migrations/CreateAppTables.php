<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Migrations;

use Module\AppStore\Domain\Models\AppFileModel;
use Module\AppStore\Domain\Models\AppModel;
use Module\AppStore\Domain\Models\AppVersionModel;
use Module\AppStore\Domain\Models\CategoryModel;
use Module\AppStore\Domain\Repositories\AppRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateAppTables extends Migration {

    public function up(): void {
        AppRepository::comment()->migration($this);
        AppRepository::tag()->migration($this);
        $this->append(CategoryModel::tableName(), function(Table $table) {
            $table->comment('分类');
            $table->id();
            $table->string('name', 30);
            $table->string('icon')->default('');
            $table->uint('parent_id')->default(0);
        })->append(AppModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->uint('user_id');
            $table->string('name', 20);
            $table->string('package_name', 60)->default('')
                ->comment('软件的包名');
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->text('content')->nullable()->comment('介绍和使用说明');
            $table->string('icon')->default('');
            $table->bool('is_free')->default(1);
            $table->bool('is_open_source')->default(1);
            $table->string('official_website')->default('')->comment('官网');
            $table->string('git_url')->default('')->comment('开源地址');
            $table->uint('comment_count')->default(0);
            $table->uint('download_count')->default(0);
            $table->uint('view_count')->default(0);
            $table->decimal('score', 4,2)->default(6)->comment('评分');
            $table->timestamps();
        })->append(AppVersionModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('app_id');
            $table->string('name', 20);
            $table->string('description')->default('')->comment('更新日志');
            $table->timestamp('created_at');
        })->append(AppFileModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('app_id');
            $table->uint('version_id');
            $table->string('name', 40)->comment('文件名');
            $table->string('os', 20)->default('')->comment('系统:windows,ios');
            $table->string('framework', 10)->default('')->comment('架构:x64,x86');
            $table->bool('url_type')->default(0)->comment('下载地址');
            $table->string('url')->default('')->comment('下载地址');
            $table->string('size', 20)->default('0')->comment('文件大小');
            $table->uint('view_count')->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void
    {
        RoleRepository::newPermission([
            'app_manage' => '应用商店管理'
        ]);
    }
}