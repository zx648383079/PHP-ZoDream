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
            $table->set('id')->pk(true);
            $table->set('user_id')->int(10);
            $table->set('content')->text()->notNull();
            $table->set('language')->varchar(20)->defaultVal('')->comment('语言');
            $table->set('recommend_count')->int(10)->defaultVal(0)->comment('推荐数');
            $table->set('collect_count')->int(10)->defaultVal(0)->comment('收藏数');
            $table->set('comment_count')->int(10)->defaultVal(0)->comment('评论数');
            $table->set('source')->varchar()->defaultVal('')->comment('来源');
            $table->timestamps();
        })->append(TagModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('code_id')->int()->notNull();
            $table->set('content')->varchar()->notNull();
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('content')->varchar()->notNull();
            $table->set('parent_id')->int(10);
            $table->set('user_id')->int(10)->defaultVal(0);
            $table->set('code_id')->int(10)->notNull();
            $table->set('agree')->int(10)->defaultVal(0);
            $table->set('disagree')->int(10)->defaultVal(0);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('id_value')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->timestamp('created_at');
        });
        parent::up();
    }
}