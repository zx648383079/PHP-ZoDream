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
            $table->string('name', 40);
            $table->uint('parent_id')->default(0);
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('thumb')->default('');
        })->append(PostModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->text('content');
            $table->string('file')->default('')->comment('下载文件');
            $table->uint('size')->default(0);
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('download_count')->default(0);
            $table->timestamps();
        })->append(LogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->string('ip', 120)->default('');
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('description')->default('');
            $table->uint('post_count')->default(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->uint('tag_id');
            $table->uint('post_id');
            $table->uint('position', 1)->default(99);
        })->autoUp();
    }
}