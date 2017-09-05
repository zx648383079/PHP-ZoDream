<?php
namespace Module\Blog\Domain\Migrations;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateBlogTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(BlogModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('title')->varchar(200)->notNull();
            $table->set('description')->varchar(200);
            $table->set('content')->text();
            $table->set('user_id')->int(10);
            $table->set('term_id')->int(10);
            $table->set('recommend')->int(10)->defaultVal(0);
            $table->set('comment_status')->tinyint(1)->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(TermModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('name')->varchar(40)->notNull();
            $table->set('parent_id')->int(10)->defaultVal(0);
            $table->set('keywords')->varchar(200);
            $table->set('description')->varchar(200)->notNull();
        });
        Schema::createTable(CommentModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('content')->varchar()->notNull();
            $table->set('name')->varchar(30);
            $table->set('email')->varchar(50);
            $table->set('url')->varchar(50);
            $table->set('parent_id')->int(10);
            $table->set('user_id')->int(10)->defaultVal(0);
            $table->set('ip')->varchar(120);
            $table->set('agent')->varchar(250);
            $table->set('agree')->int(10)->defaultVal(0);
            $table->set('disagree')->int(10)->defaultVal(0);
            $table->set('approved')->bool()->defaultVal(2);
            $this->set('created_at')->int(10)->unsigned()->defaultVal(0)->null();
        });
        Schema::createTable(BlogLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('id_value')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $this->set('created_at')->int(10)->unsigned()->defaultVal(0)->null();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(BlogModel::tableName());
        Schema::dropTable(TermModel::tableName());
        Schema::dropTable(CommentModel::tableName());
        Schema::dropTable(BlogLogModel::tableName());
    }
}