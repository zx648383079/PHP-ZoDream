<?php
namespace Module\Forum\Domain\Migrations;

use Module\Forum\Domain\Model\BlackWordModel;
use Module\Forum\Domain\Model\EmojiCategoryModel;
use Module\Forum\Domain\Model\EmojiModel;
use Module\Forum\Domain\Model\ForumClassifyModel;
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
            $table->column('name')->varchar(100);
            $table->column('thumb')->varchar(100)->default('');
            $table->column('description')->varchar()->default('');
            $table->uint('parent_id')->default(0);
            $table->uint('thread_count')->default(0)->comment('主题数');
            $table->uint('post_count')->default(0)->comment('回帖数');
            $table->column('type')->tinyint(2)->unsigned()->default(0);
            $table->column('position')->tinyint(3)->unsigned()->default(99);
            $table->timestamps();
        })->append(ForumClassifyModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(20);
            $table->column('icon')->varchar(100)->default('');
            $table->uint('forum_id')->default(0);
            $table->column('position')->tinyint(3)->default(99);
        })->append(ForumModeratorModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('forum_id')->default(0);
            $table->uint('role_id')->default(0);
        })->append(ThreadModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('forum_id');
            $table->uint('classify_id')->default(0);
            $table->column('title')->varchar(200)->comment('主题');
            $table->uint('user_id')->comment('发送用户');
            $table->uint('view_count')->default(0)->comment('查看数');
            $table->uint('post_count')->default(0)->comment('回帖数');
            $table->uint('collect_count')->default(0)->comment('关注数');
            $table->column('is_highlight')->bool()->default(0)
                ->comment('是否高亮');
            $table->column('is_digest')->bool()->default(0)
                ->comment('是否精华');
            $table->column('is_closed')->bool()->default(0)
                ->comment('是否关闭');
            $table->column('is_private_post')->bool()->default(0)->comment('是否仅楼主可见');
            $table->timestamps();
        })->append(ThreadPostModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('content')->mediumtext();
            $table->uint('thread_id');
            $table->uint('user_id')->comment('用户');
            $table->column('ip')->varchar(120);
            $table->short('grade')->unsigned()
                ->default(0)->comment('回复的层级');
            $table->column('is_invisible')->bool()->default(0)
                ->comment('是否通过审核');
            $table->uint('agree_count')->default(0)
                ->comment('赞成数');
            $table->uint('disagree_count')->default(0)
                ->comment('不赞成数');
            $table->timestamps();
        })->append(ThreadLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('item_type')->tinyint(3)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->column('node_index')->tinyint(1)->default(0)->comment('每一个回帖内部的节点');
            $table->column('data')->varchar(255)->default('')->comment('执行的参数');
            $table->timestamp('created_at');
        })->append(BlackWordModel::tableName(), function(Table $table) {
            $table->comment('违禁词');
            $table->id();
            $table->column('words')->varchar();
            $table->column('replace_words')->varchar()->default('');
        })->append(EmojiModel::tableName(), function(Table $table) {
            $table->comment('表情');
            $table->id();
            $table->uint('cat_id');
            $table->column('name')->varchar();
            $table->column('type')->tinyint(1)->default(0)->comment('图片或文字');
            $table->column('content')->varchar()->default('');
        })->append(EmojiCategoryModel::tableName(), function(Table $table) {
            $table->comment('表情分类');
            $table->id();
            $table->column('name')->varchar();
            $table->column('icon')->varchar()->default('');
        })->autoUp();
    }
}