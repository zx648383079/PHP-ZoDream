<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Navigation\Domain\Models\CategoryModel;
use Module\Navigation\Domain\Models\CollectGroupModel;
use Module\Navigation\Domain\Models\CollectModel;
use Module\Navigation\Domain\Models\KeywordModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;
use Module\Navigation\Domain\Models\SiteModel;
use Module\Navigation\Domain\Models\SiteScoringLogModel;
use Module\Navigation\Domain\Repositories\SiteRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

final class CreateNavigationTables extends Migration {
    public function up(): void {
        SiteRepository::tag()->migration($this);
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->comment('站点分类表');
            $table->id();
            $table->string('name', 30);
            $table->string('icon')->default('');
            $table->uint('parent_id')->default(0);
        })->append(SiteModel::tableName(), function (Table $table) {
            $table->comment('站点表');
            $table->id();
            $table->string('schema', 10)->default('https');
            $table->string('domain', 100);
            $table->string('name', 30);
            $table->string('logo')->default('');
            $table->string('description')->default('');
            $table->uint('cat_id')->default(0);
            $table->uint('user_id')->default(0);
            $table->uint('top_type', 1)->default(0)->comment('推荐类型');
            $table->uint('score', 1)->default(60)->comment('站点评分/百分制');
            $table->timestamps();
        })->append(SiteScoringLogModel::tableName(), function (Table $table) {
            $table->comment('站点评分记录表');
            $table->id();
            $table->uint('site_id');
            $table->uint('user_id');
            $table->uint('score', 1)->comment('站点评分/百分制');
            $table->uint('last_score', 1)->comment('上次的评分');
            $table->string('change_reason')->default('')->comment('评分变动原因');
            $table->timestamp('created_at');
        })->append(PageModel::tableName(), function (Table $table) {
            $table->comment('网页表');
            $table->id();
            $table->string('title', 30);
            $table->string('description')->default('');
            $table->string('thumb')->default('');
            $table->string('link');
            $table->string('site_id')->default(0);
            $table->uint('score', 1)->default(60)->comment('页面评分');
            $table->uint('user_id')->default(0);
            $table->timestamps();
        })->append(KeywordModel::tableName(), function (Table $table) {
            $table->comment('关键字表');
            $table->id();
            $table->string('word', 30);
            $table->uint('type', 1)->default(0)->comment('关键词类型：默认短尾词，长尾词');
        })->append(PageKeywordModel::tableName(), function (Table $table) {
            $table->comment('网页包含关键字表');
            $table->uint('page_id');
            $table->uint('word_id');
            $table->bool('is_official')->default(0)->comment('是否为关键词官网');
        })->append(CollectGroupModel::tableName(), function (Table $table) {
            $table->comment('收藏分组表');
            $table->id();
            $table->string('name', 20);
            $table->uint('user_id')->default(0);
            $table->uint('position', 1)->default(5);
        })->append(CollectModel::tableName(), function (Table $table) {
            $table->comment('收藏网页表');
            $table->id();
            $table->string('name', 20);
            $table->string('link');
            $table->uint('group_id')->default(0);
            $table->uint('user_id')->default(0);
            $table->uint('position', 1)->default(5);
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'navigation_manage' => '导航管理'
        ]);
    }
}