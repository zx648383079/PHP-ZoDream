<?php
namespace Module\Code\Domain\Migrations;

use Module\Code\Domain\Model\TagModel;
use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Model\LogModel;
use Module\Code\Domain\Model\CodeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCodeTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(CodeModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->text('content');
            $table->string('language', 20)->default('')->comment('语言');
            $table->uint('recommend_count')->default(0)->comment('推荐数');
            $table->uint('collect_count')->default(0)->comment('收藏数');
            $table->uint('comment_count')->default(0)->comment('评论数');
            $table->string('source')->default('')->comment('来源');
            $table->timestamps();
        })->append(TagModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('code_id');
            $table->string('content');
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id');
            $table->uint('user_id')->default(0);
            $table->uint('code_id');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 2)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp('created_at');
        })->autoUp();
    }
}