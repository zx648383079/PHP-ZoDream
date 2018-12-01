<?php
namespace Module\Forum\Domain\Migrations;

use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateForumTables extends Migration {

    public function up() {
        Schema::createTable(ForumModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('thumb')->varchar(100);
            $table->set('description')->varchar();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('thread_count')->int()->defaultVal(0)->comment('主题数');
            $table->set('post_count')->int()->defaultVal(0)->comment('回帖数');
            $table->set('type')->tinyint(2);
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->timestamps();
        });
        Schema::createTable(ThreadModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('forum_id')->int()->notNull();
            $table->set('title')->varchar(200)->notNull()->comment('主题');
            $table->set('user_id')->int()->notNull()->comment('发送用户');
            $table->set('view_count')->int()->defaultVal(0)->comment('查看数');
            $table->set('post_count')->int()->defaultVal(0)->comment('回帖数');
            $table->timestamps();
        });
        Schema::createTable(ThreadPostModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('content')->mediumtext()->notNull();
            $table->set('thread_id')->int()->notNull();
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->set('ip')->varchar(120)->notNull();
            $table->set('is_first')->bool()->defaultVal(0);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(ForumModel::tableName());
        Schema::dropTable(ThreadModel::tableName());
        Schema::dropTable(ThreadPostModel::tableName());
    }
}