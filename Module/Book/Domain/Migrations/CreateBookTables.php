<?php
namespace Module\Book\Domain\Migrations;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateBookTables extends Migration {

    public function up() {
        Schema::createTable(BookModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('备注');
            $table->set('group_id')->int()->notNull()->comment('分组');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamp('created_at');
        });
        Schema::createTable(BookChapterModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(2)->notNull()->defaultVal(0);
            $table->set('content')->varchar(200)->notNull()->comment('内容');
            $table->set('receive_id')->int()->notNull()->comment('接收用户');
            $table->set('user_id')->int()->notNull()->comment('发送用户');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(BookCategoryModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分组名');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamp('created_at');
        });
    }

    public function down() {
        Schema::dropTable(BookModel::tableName());
        Schema::dropTable(BookCategoryModel::tableName());
        Schema::dropTable(BookChapterModel::tableName());
    }
}