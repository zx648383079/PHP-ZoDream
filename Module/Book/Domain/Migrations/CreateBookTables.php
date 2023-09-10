<?php
declare(strict_types=1);
namespace Module\Book\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookBuyLogModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\BookMetaModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\BookRoleModel;
use Module\Book\Domain\Model\BookSourceModel;
use Module\Book\Domain\Model\BookSourceSiteModel;
use Module\Book\Domain\Model\ListItemModel;
use Module\Book\Domain\Model\RoleRelationModel;
use Module\Book\Domain\Repositories\BookRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBookTables extends Migration {

    public function up(): void {
        BookRepository::tag()->migration($this);
        BookRepository::log()->migration($this);
        BookRepository::clickLog()->migration($this);
        $this->append(BookModel::tableName(), function(Table $table) {
            $table->comment('小说');
            $table->id();
            $table->string('name', 100)->unique()->comment('书名');
            $table->string('cover', 200)->default('')->comment('封面');
            $table->string('description', 200)->default('')->comment('简介');
            $table->uint('author_id')->default(0)->comment('作者');
            $table->uint('user_id')->default(0);
            $table->uint('classify', 2)->default(0)->comment('小说分级');
            $table->uint('cat_id')->default(0)->comment('分类');
            $table->uint('size')->default(0)->comment('总字数');
            $table->uint('click_count')->default(0)->comment('点击数');
            $table->uint('recommend_count')
                ->default(0)->comment('点击数');
            $table->timestamp('over_at')->comment('完本日期');
            $table->uint('status', 2)->default(0);
            $table->uint('source_type', 2)->default(0)->comment('来源类型，原创或转载');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookMetaModel::tableName(), function(Table $table) {
            $table->comment('小说附加信息');
            $table->id();
            $table->uint('target_id');
            $table->string('name', 50);
            $table->string('content')->default('');
        })->append(BookSourceSiteModel::tableName(), function(Table $table) {
            $table->comment('小说来源站点');
            $table->id();
            $table->string('name', 30)->comment('站点名');
            $table->string('url', 100)->comment('网址');
            $table->timestamps();
        })->append(BookSourceModel::tableName(), function(Table $table) {
            $table->comment('小说来源');
            $table->id();
            $table->uint('book_id');
            $table->uint('size_id');
            $table->string('url', 200)->comment('来源网址');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterModel::tableName(), function(Table $table) {
            $table->comment('小说章节');
            $table->id();
            $table->uint('book_id');
            $table->uint('type', 1)->default(0)->comment('章节类型，是分卷还是章节');
            $table->string('title', 200)->comment('标题');
            $table->uint('parent_id')->default(0);
            $table->uint('price')->default(0);
            $table->uint('status', 2)->default(0);
            $table->uint('position', 4)->default(99);
            $table->uint('size')->default(0)->comment('字数');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookChapterBodyModel::tableName(), function(Table $table) {
            $table->comment('小说章节内容');
            $table->id();
            $table->column('content')->longtext()->comment('内容');
        })->append(BookCategoryModel::tableName(), function(Table $table) {
            $table->comment('小说分类');
            $table->id();
            $table->string('name', 100)->unique()->comment('分类名');
            $table->timestamp('created_at');
        })->append(BookAuthorModel::tableName(), function(Table $table) {
            $table->comment('小说作者');
            $table->id();
            $table->string('name', 100)->unique()->comment('作者名');
            $table->string('avatar', 200)->default('')->comment('作者头像');
            $table->string('description', 200)->default('')->comment('简介');
            $table->uint('user_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(BookHistoryModel::tableName(), function(Table $table) {
            $table->comment('小说阅读历史');
            $table->id();
            $table->uint('user_id');
            $table->uint('book_id');
            $table->uint('chapter_id')->default(0);
            $table->uint('progress', 1)->default(0);
            $table->uint('source_id')->default(0);
            $table->timestamps();
        })->append(BookBuyLogModel::tableName(), function(Table $table) {
            $table->comment('小说购买记录');
            $table->id();
            $table->uint('book_id');
            $table->uint('chapter_id');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->append(BookRoleModel::tableName(), function(Table $table) {
            $table->comment('小说角色');
            $table->id();
            $table->uint('book_id');
            $table->string('name', 50);
            $table->string('avatar', 200)->default('');
            $table->string('description', 200)->default('');
            $table->string('character', 20)->default('')->comment('身份：主角或');
            $table->string('x', 20)->default('');
            $table->string('y', 20)->default('');
        })->append(RoleRelationModel::tableName(), function(Table $table) {
            $table->comment('小说角色关系');
            $table->id();
            $table->uint('role_id');
            $table->string('title', 50);
            $table->uint('role_link');
        })->append(BookListModel::tableName(), function(Table $table) {
            $table->comment('书单');
            $table->id();
            $table->uint('user_id');
            $table->string('title', 50);
            $table->string('description', 200)->default('');
            $table->uint('book_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('collect_count')->default(0);
            $table->timestamps();
        })->append(ListItemModel::tableName(), function(Table $table) {
            $table->comment('书单书籍');
            $table->id();
            $table->uint('list_id');
            $table->uint('book_id');
            $table->string('remark', 200)->default('');
            $table->uint('star', 1)->default(10);
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'book_manage' => '书籍管理'
        ]);
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