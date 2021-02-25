<?php
namespace Module\Book\Domain\Migrations;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookBuyLogModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookClickLogModel;
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
            $table->comment('小说');
            $table->id();
            $table->column('name')->varchar(100)->unique()->notNull()->comment('书名');
            $table->column('cover')->varchar(200)->default('')->comment('封面');
            $table->column('description')->varchar(200)->default('')->comment('简介');
            $table->uint('author_id')->default(0)->comment('作者');
            $table->uint('user_id')->default(0);
            $table->column('classify')->tinyint(2)->unsigned()->default(0)->comment('小说分级');
            $table->column('cat_id')->tinyint(3)->unsigned()->default(0)->comment('分类');
            $table->uint('size')->default(0)->comment('总字数');
            $table->uint('click_count')->default(0)->comment('点击数');
            $table->uint('recommend_count')
                ->default(0)->comment('点击数');
            $table->timestamp('over_at')->comment('完本日期');
            $table->column('source')->varchar(200)->default('')->comment('来源');
            $table->uint('status', 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterModel::tableName(), function(Table $table) {
            $table->comment('小说章节');
            $table->id();
            $table->uint('book_id');
            $table->column('title')->varchar(200)->comment('标题');
            $table->uint('parent_id')->default(0);
            $table->uint('price')->default(0);
            $table->uint('status', 2)->default(0);
            $table->column('position')->tinyint(4)->default(99);
            $table->uint('size')->default(0)->comment('字数');
            $table->column('source')->varchar(200)->default('')->comment('来源');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterBodyModel::tableName(), function(Table $table) {
            $table->comment('小说章节内容');
            $table->id();
            $table->column('content')->longtext()->comment('内容');
        })->append(BookCategoryModel::tableName(), function(Table $table) {
            $table->comment('小说分类');
            $table->id();
            $table->column('name')->varchar(100)->unique()->comment('分类名');
            $table->timestamp('created_at');
        })->append(BookAuthorModel::tableName(), function(Table $table) {
            $table->comment('小说作者');
            $table->id();
            $table->column('name')->varchar(100)->unique()->comment('作者名');
            $table->column('avatar')->varchar(200)->default('')->comment('作者头像');
            $table->column('description')->varchar(200)->default('')->comment('简介');
            $table->uint('user_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(BookHistoryModel::tableName(), function(Table $table) {
            $table->comment('小说阅读历史');
            $table->id();
            $table->uint('user_id');
            $table->uint('book_id');
            $table->uint('chapter_id')->default(0);
            $table->column('progress')->tinyint(1)->default(0);
            $table->timestamps();
        })->append(BookBuyLogModel::tableName(), function(Table $table) {
            $table->comment('小说购买记录');
            $table->id();
            $table->uint('book_id');
            $table->uint('chapter_id');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->append(BookLogModel::tableName(), function(Table $table) {
            $table->comment('小说记录统计');
            $table->id();
            $table->column('item_type')->tinyint(3)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp('created_at');
        })->append(BookClickLogModel::tableName(), function(Table $table) {
            $table->comment('小说点击统计');
            $table->id();
            $table->uint('book_id');
            $table->column('hits')->int()->default(0);
            $table->column('created_at')->date();
        })->append(BookRoleModel::tableName(), function(Table $table) {
            $table->comment('小说角色');
            $table->id();
            $table->uint('book_id');
            $table->column('name')->varchar(50);
            $table->column('avatar')->varchar(200)->default('');
            $table->column('description')->varchar(200)->default('');
        })->append(RoleRelationModel::tableName(), function(Table $table) {
            $table->comment('小说角色关系');
            $table->id();
            $table->uint('role_id');
            $table->column('title')->varchar(50);
            $table->uint('role_link');
        })->append(BookListModel::tableName(), function(Table $table) {
            $table->comment('书单');
            $table->id();
            $table->uint('user_id');
            $table->column('title')->varchar(50);
            $table->column('description')->varchar(200)->default('');
            $table->uint('book_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('collect_count')->default(0);
            $table->timestamps();
        })->append(ListItemModel::tableName(), function(Table $table) {
            $table->comment('书单书籍');
            $table->id();
            $table->uint('list_id');
            $table->uint('book_id');
            $table->column('remark')->varchar(200)->default('');
            $table->column('star')->tinyint(1)->default(10);
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamps();
        })->autoUp();
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
           ['name' => '未知', 'status' => 1]
        ]);

    }
}