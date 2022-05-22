<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Migrations;

use Module\ResourceStore\Domain\Models\CommentModel;
use Module\ResourceStore\Domain\Models\LogModel;
use Module\ResourceStore\Domain\Models\ResourceFileModel;
use Module\ResourceStore\Domain\Models\ResourceModel;
use Module\ResourceStore\Domain\Models\TagModel;
use Module\ResourceStore\Domain\Models\TagRelationshipModel;
use Module\ResourceStore\Domain\Models\CategoryModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateResourceTables extends Migration {

    public function up() {
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->uint('parent_id')->default(0);
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('thumb')->default('');
        })->append(ResourceModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->text('content');
            $table->uint('size')->default(0);
            $table->uint('user_id');
            $table->uint('type', 2)->default(0)->comment('资源类型');
            $table->uint('cat_id');
            $table->uint('price')->default(0)->comment('价格');
            $table->bool('is_commercial')->default(0)->comment('是否允许商用');
            $table->bool('is_reprint')->default(0)->comment('是否允许转载');
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('download_count')->default(0);
            $table->timestamps();
        })->append(ResourceFileModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('res_id');
            $table->uint('file_type')->default(0)->comment('本地文件/网盘/种子');
            $table->string('file');
            $table->uint('click_count')->default(0);
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
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id');
            $table->uint('user_id')->default(0);
            $table->uint('res_id');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamp('created_at');
        })->autoUp();
    }
}