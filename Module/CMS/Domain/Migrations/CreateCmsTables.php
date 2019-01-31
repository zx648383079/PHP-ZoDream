<?php
namespace Module\CMS\Domain\Migrations;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateCmsTables extends Migration {

    public function up() {
        Schema::createTable(ModelFieldModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('field')->varchar(100)->notNull();
            $table->set('model_id')->int()->notNull();
            $table->set('type')->varchar(20)->defaultVal('text');
            $table->set('length')->tinyint(3);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('form_type')->tinyint(3)->defaultVal(0);
            $table->set('is_main')->bool()->defaultVal(0);
            $table->set('is_required')->bool()->defaultVal(1);
            $table->set('is_disable')->bool()->defaultVal(0)->comment('禁用/启用');
            $table->set('is_system')->bool()->defaultVal(0)
                ->comment('系统自带禁止删除');
            $table->set('match')->varchar();
            $table->set('tip_message')->varchar();
            $table->set('error_message')->varchar();
            $table->set('setting')->text();
        });
        Schema::createTable(ModelModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('table')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('category_template')->varchar(20);
            $table->set('list_template')->varchar(20);
            $table->set('show_template')->varchar(20);
            $table->set('setting')->text();
        });
        Schema::createTable(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('model_id')->int()->defaultVal(0);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar();
            $table->set('description')->varchar();
            $table->set('image')->varchar(100);
            $table->set('content')->text();
            $table->set('url')->varchar(100);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('is_menu')->bool()->defaultVal(0);
            $table->set('category_template')->varchar(20);
            $table->set('list_template')->varchar(20);
            $table->set('show_template')->varchar(20);
            $table->set('setting')->text();
            $table->timestamps();
        });
        Schema::createTable(ContentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(100)->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('keywords')->varchar();
            $table->set('thumb')->varchar();
            $table->set('description')->varchar();
            $table->set('status')->bool()->defaultVal(0);
            $table->set('view_count')->int()->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(LinkageModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->notNull();
            $table->set('code')->char(20)->unique()->notNull();
        });
        Schema::createTable(LinkageDataModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
    }

    public function down() {
        Schema::dropTable(ModelModel::tableName());
        Schema::dropTable(ModelFieldModel::tableName());
        Schema::dropTable(CategoryModel::tableName());
        Schema::dropTable(ContentModel::tableName());
        Schema::dropTable(LinkageModel::tableName());
        Schema::dropTable(LinkageDataModel::tableName());
    }
}