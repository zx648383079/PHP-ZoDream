<?php
namespace Module\Forum\Domain\Migrations;

use Module\Forum\Domain\Model\BlackWordModel;
use Module\Forum\Domain\Model\EmojiCategoryModel;
use Module\Forum\Domain\Model\EmojiModel;
use Module\Forum\Domain\Model\FileModel;
use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateForumTables extends Migration {

    public function up() {
        $this->append(ForumModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('thumb')->varchar(100);
            $table->set('description')->varchar();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('thread_count')->int()->defaultVal(0)->comment('主题数');
            $table->set('post_count')->int()->defaultVal(0)->comment('回帖数');
            $table->set('type')->tinyint(2);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->timestamps();
        })->append(ForumClassifyModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(20)->notNull();
            $table->set('icon')->varchar(100)->defaultVal('');
            $table->set('forum_id')->int()->defaultVal(0);
            $table->set('position')->tinyint(3)->defaultVal(99);
        })->append(ThreadModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('forum_id')->int()->notNull();
            $table->set('classify_id')->int()->defaultVal(0);
            $table->set('title')->varchar(200)->notNull()->comment('主题');
            $table->set('user_id')->int()->notNull()->comment('发送用户');
            $table->set('view_count')->int()->defaultVal(0)->comment('查看数');
            $table->set('post_count')->int()->defaultVal(0)->comment('回帖数');
            $table->set('collect_count')->int()->defaultVal(0)->comment('关注数');
            $table->set('is_highlight')->bool()->defaultVal(0)
                ->comment('是否高亮');
            $table->set('is_digest')->bool()->defaultVal(0)
                ->comment('是否精华');
            $table->set('is_closed')->bool()->defaultVal(0)
                ->comment('是否关闭');
            $table->set('is_private_post')->bool()->defaultVal(0)->comment('是否仅楼主可见');
            $table->timestamps();
        })->append(ThreadPostModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('content')->mediumtext()->notNull();
            $table->set('thread_id')->int()->notNull();
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->set('ip')->varchar(120)->notNull();
            $table->set('grade')->smallInt(6)
                ->defaultVal(0)->comment('回复的层级');
            $table->set('is_invisible')->bool()->defaultVal(0)
                ->comment('是否通过审核');
            $table->set('agree_count')->int()->defaultVal(0)
                ->comment('赞成数');
            $table->set('disagree_count')->int()->defaultVal(0)
                ->comment('不赞成数');
            $table->timestamps();
        })->append(ThreadLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('item_type')->tinyint(3)->defaultVal(0);
            $table->set('item_id')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->set('node_index')->tinyint(1)->defaultVal(0)->comment('每一个回帖内部的节点');
            $table->set('data')->varchar(255)->defaultVal('')->comment('执行的参数');
            $table->timestamp('created_at');
        })->append(BlackWordModel::tableName(), function(Table $table) {
            $table->setComment('违禁词');
            $table->set('id')->pk(true);
            $table->set('words')->varchar()->notNull();
            $table->set('replace_words')->varchar()->defaultVal('');
        })->append(EmojiModel::tableName(), function(Table $table) {
            $table->setComment('表情');
            $table->set('id')->pk(true);
            $table->set('cat_id')->int()->notNull();
            $table->set('name')->varchar()->notNull();
            $table->set('icon')->varchar()->notNull();
        })->append(EmojiCategoryModel::tableName(), function(Table $table) {
            $table->setComment('表情分类');
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->notNull();
        })->autoUp();
    }
}