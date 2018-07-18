<?php
namespace Module\Book\Domain\Migrations;

use Module\Auth\Domain\Model\UserMetaModel;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateBookTables extends Migration {

    public function up() {
        Schema::createTable(BookModel::tableName(), function(Table $table) {
            $table->setComment('小说');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('书名');
            $table->set('cover')->varchar(200)->defaultVal('')->comment('封面');
            $table->set('description')->varchar(200)->defaultVal('')->comment('简介');
            $table->set('author_id')->int()->defaultVal(0)->comment('作者');
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('cat_id')->tinyint(3)->defaultVal(0)->comment('分类');
            $table->set('size')->int()->defaultVal(0)->comment('总字数');
            $table->set('click_count')->int()->defaultVal(0)->comment('点击数');
            $table->set('recommend_count')->int()
                ->defaultVal(0)->comment('点击数');
            $table->timestamp('over_at')->comment('完本日期');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(BookChapterModel::tableName(), function(Table $table) {
            $table->setComment('小说章节');
            $table->set('id')->pk()->ai();
            $table->set('book_id')->int()->defaultVal(0);
            $table->set('title')->varchar(200)->comment('标题');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('position')->tinyint(4)->defaultVal(99);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(BookChapterBodyModel::tableName(), function(Table $table) {
            $table->setComment('小说章节内容');
            $table->set('id')->pk()->ai();
            $table->set('content')->longtext()->comment('内容');
        });
        Schema::createTable(BookCategoryModel::tableName(), function(Table $table) {
            $table->setComment('小说分类');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('分类名');
            $table->timestamp('created_at');
        });
        Schema::createTable(BookAuthorModel::tableName(), function(Table $table) {
            $table->setComment('小说作者');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('作者名');
            $table->set('avatar')->varchar(200)->comment('作者头像');
            $table->timestamps();
        });
        Schema::createTable(BookHistoryModel::tableName(), function(Table $table) {
            $table->setComment('小说阅读历史');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('book_id')->int()->notNull();
            $table->set('chapter_id')->int()->notNull();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(BookModel::tableName());
        Schema::dropTable(BookCategoryModel::tableName());
        Schema::dropTable(BookChapterBodyModel::tableName());
        Schema::dropTable(BookChapterModel::tableName());
        Schema::dropTable(BookAuthorModel::tableName());
    }

    public function seed() {
        BookCategoryModel::record()->insert([
            ['name' => '玄幻·奇幻'],
            ['name' => '仙侠·武侠'],
            ['name' => '都市·言情'],
            ['name' => '穿越·历史'],
            ['name' => '科幻·灵异'],
            ['name' => '同人·网游'],
        ]);

    }
}