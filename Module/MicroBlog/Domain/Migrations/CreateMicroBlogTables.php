<?php
namespace Module\MicroBlog\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\BlogTopicModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Model\TopicModel;
use Module\SEO\Domain\Option;
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
            $table->string('content', 140);
            $table->string('extra_rule', 500)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('open_type', 1)->default(0);
            $table->uint('recommend_count')->default(0)->comment('推荐数');
            $table->uint('collect_count')->default(0)->comment('收藏数');
            $table->uint('forward_count')->default(0)->comment('转发数');
            $table->uint('comment_count')->default(0)->comment('评论数');
            $table->uint('forward_id')->default(0)->comment('转发的源id');
            $table->string('source', 30)->default('')->comment('来源');
            $table->timestamps();
        })->append(AttachmentModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->string('thumb');
            $table->string('file');
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id');
            $table->uint('user_id')->default(0);
            $table->uint('micro_id');
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
        })->append(TopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 200);
            $table->uint('user_id');
            $table->timestamps();
        })->append(BlogTopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->uint('topic_id');
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'micro_manage' => '微博管理'
        ]);
        Option::group('微博客设置', function () {
            return [
                [
                    'name' => '发布间隔/秒',
                    'code' => 'micro_time_limit',
                    'type' => 'text',
                    'value' => 300,
                    'visibility' => 2,
                ],
            ];
        });
    }
}