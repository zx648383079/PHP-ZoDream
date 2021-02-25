<?php
namespace Module\CMS\Domain\Migrations;

use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCmsTables extends Migration {

    public function up() {
        $this->append(ModelFieldModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('field')->varchar(100);
            $table->uint('model_id');
            $table->column('type')->varchar(20)->default('text');
            $table->column('length')->varchar(10);
            $table->column('position')->tinyint(3)->default(99);
            $table->column('form_type')->tinyint(3)->default(0);
            $table->column('is_main')->bool()->default(0);
            $table->column('is_required')->bool()->default(1);
            $table->column('is_search')->bool()->default(0)->comment('是否能搜索');
            $table->column('is_disable')->bool()->default(0)->comment('禁用/启用');
            $table->column('is_system')->bool()->default(0)
                ->comment('系统自带禁止删除');
            $table->column('match')->varchar();
            $table->column('tip_message')->varchar();
            $table->column('error_message')->varchar();
            $table->column('tab_name')->varchar(4)->default('')->comment('编辑组名');
            $table->column('setting')->text();
        })->append(ModelModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('table')->varchar(100);
            $table->column('type')->tinyint(1)->default(0);
            $table->column('position')->tinyint(3)->default(99);
            $table->uint('child_model')->default(0)->comment('分集模型');
            $table->column('category_template')->varchar(20)->default('');
            $table->column('list_template')->varchar(20)->default('');
            $table->column('show_template')->varchar(20)->default('');
            $table->column('setting')->text()->nullable();
        })->append(GroupModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(20);
            $table->column('type')->tinyint(1)->default(0);
            $table->column('description')->varchar();
        })->append(LinkageModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('type')->tinyint(1)->default(0);
            $table->column('code')->char(20)->unique();
        })->append(LinkageDataModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('linkage_id');
            $table->column('name')->varchar(100);
            $table->uint('parent_id')->default(0);
            $table->column('position')->tinyint(3)->default(99);
            $table->column('full_name')->varchar(200);
        })->append(SiteModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('title')->varchar();
            $table->column('keywords')->varchar()->default('');
            $table->column('description')->varchar()->default('');
            $table->column('logo')->varchar()->default('');
            $table->column('theme')->varchar(100);
            $table->column('match_type')->tinyint(1)->default(0);
            $table->column('match_rule')->varchar(100)->default('');
            $table->column('is_default')->bool()->default(0);
            $table->column('options')->text()->nullable();
            $table->timestamps();
        })->autoUp();
    }

    public function seed() {
        if (SiteModel::query()->count() > 0) {
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