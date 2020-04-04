<?php
namespace Module\CMS\Domain\Migrations;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateCmsTables extends Migration {

    public function up() {
        $this->append(ModelFieldModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('field')->varchar(100)->notNull();
            $table->set('model_id')->int()->notNull();
            $table->set('type')->varchar(20)->defaultVal('text');
            $table->set('length')->varchar(10);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('form_type')->tinyint(3)->defaultVal(0);
            $table->set('is_main')->bool()->defaultVal(0);
            $table->set('is_required')->bool()->defaultVal(1);
            $table->set('is_search')->bool()->defaultVal(0)->comment('是否能搜索');
            $table->set('is_disable')->bool()->defaultVal(0)->comment('禁用/启用');
            $table->set('is_system')->bool()->defaultVal(0)
                ->comment('系统自带禁止删除');
            $table->set('match')->varchar();
            $table->set('tip_message')->varchar();
            $table->set('error_message')->varchar();
            $table->set('tab_name')->varchar(4)->defaultVal('')->comment('编辑组名');
            $table->set('setting')->text();
        })->append(ModelModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('table')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('child_model')->int(10, true)->defaultVal(0)->comment('分集模型');
            $table->set('category_template')->varchar(20)->defaultVal('');
            $table->set('list_template')->varchar(20)->defaultVal('');
            $table->set('show_template')->varchar(20)->defaultVal('');
            $table->set('setting')->text();
        })->append(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('model_id')->int()->defaultVal(0);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('thumb')->varchar(100)->defaultVal('')->comment('缩略图');
            $table->set('image')->varchar(100)->defaultVal('')->comment('主图');
            $table->set('content')->text();
            $table->set('url')->varchar(100)->defaultVal('');
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('groups')->varchar()->defaultVal('');
            $table->set('category_template')->varchar(20)->defaultVal('');
            $table->set('list_template')->varchar(20)->defaultVal('');
            $table->set('show_template')->varchar(20)->defaultVal('');
            $table->set('setting')->text();
            $table->timestamps();
        })->append(GroupModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(20)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('description')->varchar();
        })->append(ContentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(100)->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('model_id')->int()->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar();
            $table->set('thumb')->varchar();
            $table->set('description')->varchar();
            $table->set('status')->bool()->defaultVal(0);
            $table->set('view_count')->int()->defaultVal(0);
            $table->timestamps();
        })->append(LinkageModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('code')->char(20)->unique()->notNull();
        })->append(LinkageDataModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('linkage_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('full_name')->varchar(200)->notNull();
        });
        parent::up();
    }
}