<?php
namespace Module\Book\Domain\Migrations;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\BookLogModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\BookRoleModel;
use Module\Book\Domain\Model\ListItemModel;
use Module\Book\Domain\Model\RoleRelationModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBookTables extends Migration {

    public function up() {
        $this->append(BookModel::tableName(), function(Table $table) {
            $table->setComment('小说');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('书名');
            $table->set('cover')->varchar(200)->defaultVal('')->comment('封面');
            $table->set('description')->varchar(200)->defaultVal('')->comment('简介');
            $table->set('author_id')->int()->defaultVal(0)->comment('作者');
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('classify')->tinyint(2)->defaultVal(0)->comment('小说分级');
            $table->set('cat_id')->tinyint(3)->defaultVal(0)->comment('分类');
            $table->set('size')->int()->unsigned()->defaultVal(0)->comment('总字数');
            $table->set('click_count')->int()->defaultVal(0)->comment('点击数');
            $table->set('recommend_count')->int()->unsigned()
                ->defaultVal(0)->comment('点击数');
            $table->timestamp('over_at')->comment('完本日期');
            $table->set('source')->varchar(200)->defaultVal('')->comment('来源');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterModel::tableName(), function(Table $table) {
            $table->setComment('小说章节');
            $table->set('id')->pk()->ai();
            $table->set('book_id')->int()->defaultVal(0);
            $table->set('title')->varchar(200)->comment('标题');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('position')->tinyint(4)->defaultVal(99);
            $table->set('size')->int()->unsigned()->defaultVal(0)->comment('字数');
            $table->set('source')->varchar(200)->defaultVal('')->comment('来源');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterBodyModel::tableName(), function(Table $table) {
            $table->setComment('小说章节内容');
            $table->set('id')->pk()->ai();
            $table->set('content')->longtext()->comment('内容');
        })->append(BookCategoryModel::tableName(), function(Table $table) {
            $table->setComment('小说分类');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('分类名');
            $table->timestamp('created_at');
        })->append(BookAuthorModel::tableName(), function(Table $table) {
            $table->setComment('小说作者');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->unique()->notNull()->comment('作者名');
            $table->set('avatar')->varchar(200)->comment('作者头像');
            $table->timestamps();
        })->append(BookHistoryModel::tableName(), function(Table $table) {
            $table->setComment('小说阅读历史');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('book_id')->int()->notNull();
            $table->set('chapter_id')->int()->defaultVal(0);
            $table->set('progress')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(BookLogModel::tableName(), function(Table $table) {
            $table->setComment('小说点击统计');
            $table->set('id')->pk()->ai();
            $table->set('book_id')->int()->notNull();
            $table->set('hits')->int()->defaultVal(0);
            $table->set('created_at')->date()->notNull();
        })->append(BookRoleModel::tableName(), function(Table $table) {
            $table->setComment('小说角色');
            $table->set('id')->pk()->ai();
            $table->set('book_id')->int()->notNull();
            $table->set('name')->varchar(50)->notNull();
            $table->set('avatar')->varchar(200)->defaultVal('');
            $table->set('description')->varchar(200)->defaultVal('');
        })->append(RoleRelationModel::tableName(), function(Table $table) {
            $table->setComment('小说角色关系');
            $table->set('id')->pk()->ai();
            $table->set('role_id')->int()->notNull();
            $table->set('title')->varchar(50)->notNull();
            $table->set('role_link')->int()->notNull();
        })->append(BookListModel::tableName(), function(Table $table) {
            $table->setComment('书单');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('title')->varchar(50)->notNull();
            $table->set('description')->varchar(200)->defaultVal('');
            $table->timestamps();
        })->append(ListItemModel::tableName(), function(Table $table) {
            $table->setComment('书单书籍');
            $table->set('id')->pk()->ai();
            $table->set('list_id')->int()->notNull();
            $table->set('book_id')->int()->notNull();
            $table->set('remark')->varchar(200)->defaultVal('');
            $table->timestamps();
        });
        parent::up();
    }

    public function seed() {
        if (BookCategoryModel::query()->count() > 0) {
            return;
        }
        BookCategoryModel::query()->insert([
            ['name' => '玄幻'],
            ['name' => '奇幻'],
            ['name' => '仙侠'],
            ['name' => '武侠'],
            ['name' => '都市'],
            ['name' => '言情'],
            ['name' => '穿越'],
            ['name' => '历史'],
            ['name' => '科幻'],
            ['name' => '灵异'],
            ['name' => '同人'],
            ['name' => '网游'],
        ]);
        BookAuthorModel::query()->insert([
           ['name' => '未知']
        ]);

    }
}