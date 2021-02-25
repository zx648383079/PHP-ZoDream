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
            $table->id();
            $table->column('name')->varchar(40);
            $table->uint('parent_id')->default(0);
            $table->column('keywords')->varchar()->default('');
            $table->column('description')->varchar()->default('');
            $table->column('thumb')->varchar()->default('');
        })->append(PostModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('title')->varchar(200);
            $table->column('description')->varchar()->default('');
            $table->column('keywords')->varchar()->default('');
            $table->column('thumb')->varchar()->default('');
            $table->column('content')->text();
            $table->column('file')->varchar()->default('')->comment('下载文件');
            $table->uint('size')->default(0);
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('download_count')->default(0);
            $table->timestamps();
        })->append(LogModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('item_type')->tinyint(1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->column('ip')->varchar(120)->default('');
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(40);
            $table->column('description')->varchar();
            $table->column('post_count')->int()->default(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->uint('tag_id');
            $table->uint('post_id');
            $table->column('position')->tinyint(1)->unsigned()->default(99);
        })->autoUp();
    }
}