<?php
declare(strict_types=1);
namespace Module\Book\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Book\Domain\Entities\AuthorEntity;
use Module\Book\Domain\Entities\BuyLogEntity;
use Module\Book\Domain\Entities\CategoryEntity;
use Module\Book\Domain\Entities\ChapterBodyEntity;
use Module\Book\Domain\Entities\ChapterEntity;
use Module\Book\Domain\Entities\HistoryEntity;
use Module\Book\Domain\Entities\ListEntity;
use Module\Book\Domain\Entities\BookMetaEntity;
use Module\Book\Domain\Entities\BookEntity;
use Module\Book\Domain\Entities\BookEventEntity;
use Module\Book\Domain\Entities\BookRoleEntity;
use Module\Book\Domain\Entities\ListItemEntity;
use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\ChapterRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBookTables extends Migration {

    public function up(): void {
        BookRepository::tag()->migration($this);
        BookRepository::log()->migration($this);
        BookRepository::clickLog()->migration($this);
        BookRepository::score()->migration($this);
        $this->append(BookEntity::tableName(), function(Table $table) {
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
                ->default(0)->comment('推荐数');
            $table->timestamp('over_at')->comment('完本日期');
            $table->uint('status', 2)->default(0);
            $table->uint('source_type', 2)->default(0)->comment('来源类型，原创或转载');
            $table->softDeletes();
            $table->timestamps();
        })->append(BookMetaEntity::tableName(), function(Table $table) {
            $table->comment('小说附加信息');
            $table->id();
            $table->uint('target_id');
            $table->string('name', 50);
            $table->string('content')->default('');
        })->append(ChapterEntity::tableName(), function(Table $table) {
            $table->comment('小说章节');
            $table->id();
            $table->uint('book_id');
            $table->uint('type', 1)->default(ChapterRepository::TYPE_FREE_CHAPTER)->comment('章节类型，是分卷还是章节');
            $table->string('title', 200)->comment('标题');
            $table->uint('parent_id')->default(0);
            $table->uint('price')->default(0);
            $table->uint('status', 2)->default(0);
            $table->uint('position', 4)->default(99);
            $table->uint('size')->default(0)->comment('字数');
            $table->softDeletes();
            $table->timestamps();
        })->append(ChapterBodyEntity::tableName(), function(Table $table) {
            $table->comment('小说章节内容');
            $table->id();
            $table->column('content')->longtext()->comment('内容');
        })->append(CategoryEntity::tableName(), function(Table $table) {
            $table->comment('小说分类');
            $table->id();
            $table->string('name', 100)->unique()->comment('分类名');
            $table->timestamp('created_at');
        })->append(AuthorEntity::tableName(), function(Table $table) {
            $table->comment('小说作者');
            $table->id()->ai(100);
            $table->string('name', 100)->unique()->comment('作者名');
            $table->string('avatar', 200)->default('')->comment('作者头像');
            $table->string('description', 200)->default('')->comment('简介');
            $table->uint('user_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(HistoryEntity::tableName(), function(Table $table) {
            $table->comment('小说阅读历史');
            $table->id();
            $table->uint('user_id');
            $table->uint('book_id');
            $table->uint('chapter_id')->default(0);
            $table->uint('progress', 1)->default(0);
            $table->uint('source_id')->default(0);
            $table->timestamps();
        })->append(BuyLogEntity::tableName(), function(Table $table) {
            $table->comment('小说购买记录');
            $table->id();
            $table->uint('book_id');
            $table->uint('chapter_id');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->append(BookRoleEntity::tableName(), function(Table $table) {
            $table->comment('小说角色');
            $table->id();
            $table->uint('book_id');
            $table->string('name', 50);
            $table->string('avatar', 200)->default('');
            $table->string('description', 200)->default('');
            $table->string('identity', 20)->default('')->comment('身份：主角或');
            $table->string('properties', 200)->default('');
        })->append(BookEventEntity::tableName(), function(Table $table) {
            $table->comment('小说事件大纲');
            $table->id();
            $table->uint('book_id');
            $table->string('name', 200);
            $table->string('location', 200)->default('')->comment('地点');
            $table->string('role', 200)->default('')->comment('设计角色');
            $table->text('time_node', 200)->default('')->comment('时间节点');
            $table->timestamps();
        })->append(ListEntity::tableName(), function(Table $table) {
            $table->comment('书单');
            $table->id();
            $table->uint('user_id');
            $table->string('title', 50);
            $table->string('description', 200)->default('');
            $table->uint('book_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('collect_count')->default(0);
            $table->timestamps();
        })->append(ListItemEntity::tableName(), function(Table $table) {
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
        if (CategoryEntity::query()->any()) {
            return;
        }
        CategoryEntity::query()->insert([
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
        AuthorEntity::query()->insert([
           ['id' => 1, 'name' => '未知', 'status' => 1]
        ]);

    }
}