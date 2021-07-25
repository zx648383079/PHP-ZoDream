<?php
namespace Module\Forum\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumLogModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ForumModeratorModel;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateForumTables extends Migration {

    public function up() {
        $this->append(ForumModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('thumb', 100)->default('');
            $table->string('description')->default('');
            $table->uint('parent_id')->default(0);
            $table->uint('thread_count')->default(0)->comment('主题数');
            $table->uint('post_count')->default(0)->comment('回帖数');
            $table->uint('type', 2)->default(0);
            $table->uint('position', 2)->default(99);
            $table->timestamps();
        })->append(ForumClassifyModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('icon', 100)->default('');
            $table->uint('forum_id')->default(0);
            $table->uint('position', 2)->default(99);
        })->append(ForumModeratorModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('forum_id')->default(0);
            $table->uint('role_id')->default(0);
        })->append(ThreadModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('forum_id');
            $table->uint('classify_id')->default(0);
            $table->string('title', 200)->comment('主题');
            $table->uint('user_id')->comment('发送用户');
            $table->uint('view_count')->default(0)->comment('查看数');
            $table->uint('post_count')->default(0)->comment('回帖数');
            $table->uint('collect_count')->default(0)->comment('关注数');
            $table->bool('is_highlight')->default(0)
                ->comment('是否高亮');
            $table->bool('is_digest')->default(0)
                ->comment('是否精华');
            $table->bool('is_closed')->default(0)
                ->comment('是否关闭');
            $table->uint('top_type', 1)->default(0)
                ->comment('置顶类型，1 本版置顶 2 分类置顶 3 全局置顶');
            $table->bool('is_private_post')->default(0)->comment('是否仅楼主可见');
            $table->timestamps();
        })->append(ThreadPostModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('content')->mediumtext();
            $table->uint('thread_id');
            $table->uint('user_id')->comment('用户');
            $table->string('ip', 120);
            $table->uint('grade', 5)
                ->default(0)->comment('回复的层级');
            $table->bool('is_invisible')->default(0)
                ->comment('是否通过审核');
            $table->uint('agree_count')->default(0)
                ->comment('赞成数');
            $table->uint('disagree_count')->default(0)
                ->comment('不赞成数');
            $table->uint('status', 1)->default(0)
                ->comment('帖子的状态');
            $table->timestamps();
        })->append(ThreadLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 2)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->uint('node_index', 1)->default(0)->comment('每一个回帖内部的节点');
            $table->string('data')->default('')->comment('执行的参数');
            $table->timestamp('created_at');
        })->append(ForumLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 2)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->string('data')->default('')->comment('执行的参数');
            $table->timestamp('created_at');
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'forum_manage' => '论坛管理'
        ]);
    }
}