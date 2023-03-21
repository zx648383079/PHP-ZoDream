<?php
declare(strict_types=1);
namespace Module\Template\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Template\Domain\Entities\SiteComponentEntity;
use Module\Template\Domain\Entities\SiteEntity;
use Module\Template\Domain\Entities\SitePageEntity;
use Module\Template\Domain\Entities\SitePageWeightEntity;
use Module\Template\Domain\Entities\SiteWeightEntity;
use Module\Template\Domain\Entities\ThemeCategoryEntity;
use Module\Template\Domain\Entities\ThemeComponentEntity;
use Module\Template\Domain\Entities\ThemeStyleEntity;
use Module\Template\Domain\Repositories\PageRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTemplateTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(ThemeCategoryEntity::tableName(), function(Table $table) {
            $table->comment('主题市场分类');
            $table->id();
            $table->string('name', 20);
            $table->uint('parent_id')->default(0);
            $table->string('description')->default('');
            $table->string('thumb', 100)->default('');
        })->append(ThemeComponentEntity::tableName(), function(Table $table) {
            $table->comment('主题市场的部件，包含页面和组件');
            $table->id();
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->uint('cat_id');
            $table->uint('user_id');
            $table->uint('price')->default(0);
            $table->uint('type', 1)->default(0)->comment('部件类型');
            $table->string('author', 20)->default('');
            $table->string('version', 10)->default('');
            $table->uint('status', 1)->default(0)->comment('需要审核');
            $table->string('path', 200);
            $table->timestamps();
        })->append(ThemeStyleEntity::tableName(), function(Table $table) {
            $table->comment('页面和组件提供的样式');
            $table->id();
            $table->uint('component_id');
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->string('path', 200);
        })->append(SiteComponentEntity::tableName(), function(Table $table) {
            $table->comment('站点需要的页面和部件');
            $table->id();
            $table->uint('component_id');
            $table->uint('site_id');
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->uint('type', 1)->default(0)->comment('部件类型');
            $table->string('author', 20)->default('');
            $table->string('version', 10)->default('');
            $table->string('path', 200);
            $table->timestamps();
        })->append(SiteEntity::tableName(), function(Table $table) {
            $table->comment('自定义站点');
            $table->id();
            $table->string('name', 100);
            $table->uint('user_id');
            $table->string('title', 200)->default('New Site');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('logo')->default('');
            $table->string('description')->default('');
            $table->string('domain', 50)->default('');
            $table->uint('default_page_id')->default(0)->comment('默认首页');
            $table->bool('is_share')->default(0)->comment('是否共享，允许其他人复制');
            $table->uint('share_price')->default(0)->comment('共享是否需要购买');
            $table->uint('status', 1)->default(PageRepository::PUBLISH_STATUS_DRAFT)->comment('发布状态');
            $table->timestamps();
        })->append(SiteWeightEntity::tableName(), function(Table $table) {
            $table->comment('站点的所有自定义组件及设置');
            $table->id();
            $table->uint('site_id');
            $table->uint('component_id');
            $table->string('title', 200)->default('');
            $table->text('content')->nullable();
            $table->text('settings')->nullable();
            $table->uint('style_id')->default(0);
            $table->bool('is_share')->default(0);
            $table->timestamps();
        })->append(SitePageEntity::tableName(), function(Table $table) {
            $table->comment('自定义站点页面');
            $table->id();
            $table->uint('site_id');
            $table->uint('component_id');
            $table->uint('type', 2)->default(0);
            $table->string('name', 100);
            $table->string('title', 200)->default('New Page');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('description')->default('');
            $table->text('settings')->nullable();
            $table->uint('position', 2)->default(10);
            $table->string('dependencies')->default('依赖的脚本和css文件');
            $table->uint('status', 1)->default(PageRepository::PUBLISH_STATUS_DRAFT)->comment('发布状态');
            $table->timestamps();
        })->append(SitePageWeightEntity::tableName(), function(Table $table) {
            $table->comment('自定义页面组件及设置');
            $table->id();
            $table->uint('page_id');
            $table->uint('site_id');
            $table->uint('weight_id');
            $table->uint('parent_id')->default(0);
            $table->uint('parent_index', 2)
                ->default(0)->comment('在父元素那个位置上');
            $table->uint('position', 5)->default(99);
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'visual_manage' => '模板管理'
        ]);
        if (ThemeCategoryEntity::query()->count() > 0) {
            return;
        }
        ThemeCategoryEntity::query()->insert([
            [
                'name' => '页面'
            ],
            [
                'name' => '组件'
            ],
        ]);
    }
}