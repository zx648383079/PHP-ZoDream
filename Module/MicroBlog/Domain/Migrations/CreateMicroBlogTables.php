<?php
namespace Module\MicroBlog\Domain\Migrations;

use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
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
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int(10);
            $table->set('content')->varchar(140)->notNull();
            $table->set('recommend_count')->int(10)->defaultVal(0)->comment('推荐数');
            $table->set('forward_count')->int(10)->defaultVal(0)->comment('转发数');
            $table->set('comment_count')->int(10)->defaultVal(0)->comment('评论数');
            $table->set('forward_id')->int(10)->defaultVal(0)->comment('转发的源id');
            $table->set('source')->varchar(30)->defaultVal('')->comment('来源');
            $table->timestamps();
        })->append(AttachmentModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('micro_id')->int()->notNull();
            $table->set('thumb')->varchar()->notNull();
            $table->set('file')->varchar()->notNull();
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('content')->varchar()->notNull();
            $table->set('parent_id')->int(10);
            $table->set('user_id')->int(10)->defaultVal(0);
            $table->set('micro_id')->int(10)->notNull();
            $table->set('agree')->int(10)->defaultVal(0);
            $table->set('disagree')->int(10)->defaultVal(0);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('id_value')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->timestamp('created_at');
        });
        parent::up();
    }
}