<?php
namespace Module\MicroBlog\Domain\Migrations;

use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\BlogTopicModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Model\TopicModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateMicroBlogTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(MicroBlogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('content')->varchar(140);
            $table->uint('open_type', 1)->default(0);
            $table->uint('recommend_count')->default(0)->comment('推荐数');
            $table->uint('collect_count')->default(0)->comment('收藏数');
            $table->uint('forward_count')->default(0)->comment('转发数');
            $table->uint('comment_count')->default(0)->comment('评论数');
            $table->uint('forward_id')->default(0)->comment('转发的源id');
            $table->column('source')->varchar(30)->default('')->comment('来源');
            $table->timestamps();
        })->append(AttachmentModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->column('thumb')->varchar();
            $table->column('file')->varchar();
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('content')->varchar();
            $table->uint('parent_id');
            $table->uint('user_id')->default(0);
            $table->uint('micro_id');
            $table->uint('agree')->default(0);
            $table->uint('disagree')->default(0);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('type', 2)->default(0);
            $table->uint('id_value');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp('created_at');
        })->append(TopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(200);
            $table->uint('user_id');
            $table->timestamps();
        })->append(BlogTopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->uint('topic_id');
        })->autoUp();
    }
}