<?php
namespace Module\Demo\Domain\Migrations;


use Module\Demo\Domain\Model\LogModel;
use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Model\TagModel;
use Module\Demo\Domain\Model\TagRelationshipModel;
use Module\Demo\Domain\Model\CategoryModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateDemoTables extends Migration {

    public function up() {
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('parent_id')->int(10)->defaultVal(0);
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('thumb')->varchar()->defaultVal('');
        })->append(PostModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(200)->notNull();
            $table->set('description')->varchar()->defaultVal('');
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('thumb')->varchar()->defaultVal('');
            $table->set('content')->text();
            $table->set('file')->varchar()->defaultVal('')->comment('下载文件');
            $table->set('size')->int(10, true)->defaultVal(0);
            $table->set('user_id')->int(10, true)->notNull();
            $table->set('cat_id')->int(10, true)->notNull();
            $table->set('comment_count')->int(10)->unsigned()->defaultVal(0);
            $table->set('click_count')->int(10)->unsigned()->defaultVal(0);
            $table->set('download_count')->int(10)->unsigned()->defaultVal(0);
            $table->timestamps();
        })->append(LogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('item_type')->tinyint(1)->defaultVal(0);
            $table->set('item_id')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('description')->varchar();
            $table->set('post_count')->int()->defaultVal(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->set('tag_id')->int()->notNull();
            $table->set('post_id')->int()->notNull();
            $table->set('position')->tinyint(1)->defaultVal(99);
        })->autoUp();
    }
}