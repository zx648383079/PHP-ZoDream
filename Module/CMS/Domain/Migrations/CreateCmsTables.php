<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\CMS\Domain\Entities\GroupEntity;
use Module\CMS\Domain\Entities\LinkageDataEntity;
use Module\CMS\Domain\Entities\LinkageEntity;
use Module\CMS\Domain\Entities\ModelEntity;
use Module\CMS\Domain\Entities\ModelFieldEntity;
use Module\CMS\Domain\Entities\RecycleBinEntity;
use Module\CMS\Domain\Entities\SiteEntity;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Schema\Table;

class CreateCmsTables extends Migration {

    public function up(): void {
        $this->append(ModelFieldEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('field', 100);
            $table->uint('model_id');
            $table->string('type', 20)->default('text');
            $table->uint('length', 10)->default(0);
            $table->uint('position', 2)->default(99);
            $table->uint('form_type', 2)->default(0);
            $table->bool('is_main')->default(0);
            $table->bool('is_required')->default(1);
            $table->bool('is_search')->default(0)->comment('是否能搜索');
            $table->bool('is_disable')->default(0)->comment('禁用/启用');
            $table->bool('is_system')->default(0)
                ->comment('系统自带禁止删除');
            $table->string('match')->default('');
            $table->string('tip_message')->default('');
            $table->string('error_message')->default('');
            $table->string('tab_name', 4)->default('')->comment('编辑组名');
            $table->text('setting')->nullable();
        })->append(ModelEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('table', 100);
            $table->uint('type', 2)->default(0);
            $table->uint('position', 2)->default(99);
            $table->uint('child_model')->default(0)->comment('分集模型');
            $table->string('category_template', 20)->default('');
            $table->string('list_template', 20)->default('');
            $table->string('show_template', 20)->default('');
            $table->string('edit_template', 20)->default('');
            $table->text('setting')->nullable();
        })->append(GroupEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 20);
            $table->uint('type', 2)->default(0);
            $table->string('description')->default('');
        })->append(LinkageEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->uint('type', 2)->default(0);
            $table->char('code', 20);
            $table->string('language', 10)->default('');
        })->append(LinkageDataEntity::tableName(), function (Table $table) {
            $table->id();
            $table->uint('linkage_id');
            $table->string('name', 100);
            $table->uint('parent_id')->default(0);
            $table->uint('position', 2)->default(99);
            $table->string('description')->default('');
            $table->string('thumb')->default('');
            $table->string('full_name', 200)->default('');
        })->append(SiteEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('title');
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('logo')->default('');
            $table->string('language', 10)->default('');
            $table->string('theme', 100);
            $table->uint('match_type', 2)->default(0);
            $table->string('match_rule', 100)->default('');
            $table->bool('is_default')->default(0);
            $table->uint('status', 1)->default(SiteRepository::PUBLISH_STATUS_POSTED)
                ->comment('发布状态');
            $table->text('options')->nullable();
            $table->timestamps();
        })->append(RecycleBinEntity::tableName(), function (Table $table) {
            $table->id();
            $table->uint('site_id')->default(0);
            $table->uint('model_id')->default(0);
            $table->uint('item_type',1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id')->comment('删除者');
            $table->string('title');
            $table->string('remark')->default('');
            $table->text('data');
            $table->timestamp(Model::CREATED_AT);
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'cms_manage' => 'CMS管理'
        ]);
        if (SiteEntity::query()->count() > 0) {
            return;
        }
        CMSRepository::generateSite(SiteModel::create([
            'title' => '默认站点',
            'keywords' => '',
            'description' => '',
            'theme' => 'default',
            'match_type' => 0,
            'match_rule' => '',
            'is_default' => 1,
        ]));
    }
}