<?php
namespace Module\Blog\Domain\Migrations;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBlogTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(BlogModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('title')->varchar(200);
            $table->column('description')->varchar()->default('');
            $table->column('keywords')->varchar()->default('');
            $table->uint('parent_id')->default(0);
            $table->column('programming_language')->varchar(20)
                ->default('')->comment('编程语言');
            $table->column('language')->enum(['zh', 'en'])->default('zh')
                ->comment('内容语言');
            $table->column('thumb')->varchar()->default('');
            $table->column('edit_type')->tinyint(1)->unsigned()->default(BlogModel::EDIT_HTML)->comment('编辑器类型');
            $table->column('content')->text();
            $table->uint('user_id');
            $table->uint('term_id');
            $table->column('type')->bool()->unsigned()->default(BlogModel::TYPE_ORIGINAL)->comment('原创或转载');
            $table->uint('recommend')->default(0);
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->column('open_type')->tinyint(1)->unsigned()->default(0)->comment('公开类型');
            $table->column('open_rule')->varchar(20)->default('')->comment('类型匹配的值');
            $table->softDeletes();
            $table->timestamps();
        })->append(BlogMetaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('blog_id');
            $table->column('name')->varchar(100);
            $table->column('content')->text();
        })->append(TermModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->uint('parent_id');
            $table->column('keywords')->varchar()->default('');
            $table->column('description')->varchar()->default('');
            $table->column('thumb')->varchar()->default('');
            $table->column('styles')->varchar()->default('')->comment('独立引入样式');
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('content')->varchar();
            $table->column('name')->varchar(30)->default('');
            $table->column('email')->varchar(50)->default('');
            $table->column('url')->varchar(50)->default('');
            $table->uint('parent_id')->default(0);
            $table->column('position')->int(10)->default(1);
            $table->uint('user_id')->default(0);
            $table->uint('blog_id');
            $table->column('ip')->varchar(120)->default('');
            $table->column('agent')->varchar(250)->default('');
            $table->column('agree')->int(10)->default(0);
            $table->column('disagree')->int(10)->default(0);
            $table->column('approved')->bool()->default(2);
            $table->timestamp('created_at');
        })->append(BlogLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('type')->tinyint(3)->default(0);
            $table->uint('id_value');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar(40);
            $table->column('description')->varchar()->default('');
            $table->column('blog_count')->int()->default(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->uint('tag_id');
            $table->uint('blog_id');
            $table->column('position')->tinyint(3)->unsigned()->default(99);
        })->autoUp();
    }
}