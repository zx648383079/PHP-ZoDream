<?php
namespace Module\Blog\Domain\Migrations;

use Domain\Repositories\LocalizeRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Blog\Domain\Model\BlogClickLogModel;
use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\PublishRepository;
use Module\SEO\Domain\Option;
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
            $table->string('title', 200);
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->uint('parent_id')->default(0);
            $table->string('programming_language', 20)
                ->default('')->comment('编程语言');
            LocalizeRepository::addTableColumn($table);
            $table->string('thumb')->default('');
            $table->uint('edit_type', 1)->default(PublishRepository::EDIT_HTML)->comment('编辑器类型');
            $table->text('content');
            $table->uint('user_id');
            $table->uint('term_id');
            $table->bool('type')->default(PublishRepository::TYPE_ORIGINAL)->comment('原创或转载');
            $table->uint('recommend_count')->default(0);
            $table->uint('comment_count')->default(0);
            $table->uint('click_count')->default(0);
            $table->uint('open_type', 1)->default(PublishRepository::OPEN_PUBLIC)->comment('公开类型');
            $table->string('open_rule', 20)->default('')->comment('类型匹配的值');
            $table->uint('publish_status', 1)->default(PublishRepository::PUBLISH_STATUS_DRAFT)->comment('发布状态');
            $table->timestamps();
        })->append(BlogMetaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('blog_id');
            $table->string('name', 100);
            $table->text('content');
        })->append(TermModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->uint('parent_id');
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('thumb')->default('');
            $table->string('styles')->default('')->comment('独立引入样式');
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->string('name', 30)->default('');
            $table->string('email', 50)->default('');
            $table->string('url', 50)->default('');
            $table->uint('parent_id')->default(0);
            $table->uint('position')->default(1);
            $table->uint('user_id')->default(0);
            $table->uint('blog_id');
            $table->string('ip', 120)->default('');
            $table->string('agent')->default('');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->uint('approved', 1)->default(2);
            $table->timestamp('created_at');
        })->append(BlogLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('description')->default('');
            $table->uint('blog_count')->default(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->uint('tag_id');
            $table->uint('blog_id');
            $table->uint('position', 2)->default(99);
        })->append(BlogClickLogModel::tableName(), function(Table $table) {
            $table->id();
            $table->date('happen_day');
            $table->uint('blog_id');
            $table->uint('click_count')->default(0);
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'blog_term_edit' => '博客分类管理'
        ]);
        Option::group('博客设置', function () {
            return [
                [
                    'name' => '博客列表显示',
                    'code' => 'blog_list_view',
                    'type' => 'select',
                    'value' => 0,
                    'default_value' => "无图\n左图\n右图",
                    'visibility' => 2,
                ],
                [
                    'name' => '博客评论',
                    'code' => 'blog_comment',
                    'type' => 'select',
                    'value' => 0,
                    'default_value' => "关闭\n开启\n仅登录",
                    'visibility' => 2,
                ],
                [
                    'name' => '评论审核',
                    'code' => 'comment_approved',
                    'type' => 'switch',
                    'value' => 0,
                    'default_value' => "关闭\n开启",
                    'visibility' => 2,
                ],
            ];
        });
    }
}