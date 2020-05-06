<?php
namespace Module\Blog\Domain\Migrations;

use Module\Blog\Domain\Model\BlogLogModel;
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
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(200)->notNull();
            $table->set('description')->varchar()->defaultVal('');
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('parent_id')->int()->unsigned()->defaultVal(0);
            $table->set('programming_language')->varchar(20)
                ->defaultVal('')->comment('编程语言');
            $table->set('language')->enum(['zh', 'en'])->defaultVal('zh')
                ->comment('内容语言');
            $table->set('thumb')->varchar()->defaultVal('');
            $table->set('edit_type')->tinyint(1)->unsigned()->defaultVal(BlogModel::EDIT_HTML)->comment('编辑器类型');
            $table->set('content')->text();
            $table->set('user_id')->int(10)->unsigned();
            $table->set('term_id')->int(10)->unsigned();
            $table->set('type')->bool()->unsigned()->defaultVal(BlogModel::TYPE_ORIGINAL)->comment('原创或转载');
            $table->set('source_url')->varchar(100)->defaultVal('')->comment('原文链接');
            $table->set('audio_url')->varchar(100)->defaultVal('')->comment('音频链接');
            $table->set('video_url')->varchar(100)->defaultVal('')->comment('视频链接');
            $table->set('recommend')->int(10)->unsigned()->defaultVal(0);
            $table->set('comment_count')->int(10)->unsigned()->defaultVal(0);
            $table->set('click_count')->int(10)->unsigned()->defaultVal(0);
            $table->set('comment_status')->tinyint(1)->unsigned()->defaultVal(0);
            $table->set('open_type')->tinyint(1)->unsigned()->defaultVal(0)->comment('公开类型');
            $table->set('open_rule')->varchar(20)->defaultVal('')->comment('类型匹配的值');
            $table->set('weather')->varchar(20)->defaultVal('')->comment('天气');
            $table->softDeletes();
            $table->timestamps();
        })->append(TermModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('parent_id')->int(10)->defaultVal(0);
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('thumb')->varchar()->defaultVal('');
            $table->set('styles')->varchar()->defaultVal('')->comment('独立引入样式');
        })->append(CommentModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('content')->varchar()->notNull();
            $table->set('name')->varchar(30);
            $table->set('email')->varchar(50);
            $table->set('url')->varchar(50);
            $table->set('parent_id')->int(10);
            $table->set('position')->int(10)->defaultVal(1);
            $table->set('user_id')->int(10)->defaultVal(0);
            $table->set('blog_id')->int(10)->notNull();
            $table->set('ip')->varchar(120);
            $table->set('agent')->varchar(250);
            $table->set('agree')->int(10)->defaultVal(0);
            $table->set('disagree')->int(10)->defaultVal(0);
            $table->set('approved')->bool()->defaultVal(2);
            $table->timestamp('created_at');
        })->append(BlogLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('id_value')->int(10)->notNull();
            $table->set('user_id')->int(10)->notNull();
            $table->set('action')->int(10)->notNull();
            $table->timestamp('created_at');
        })->append(TagModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull();
            $table->set('description')->varchar();
            $table->set('blog_count')->int()->defaultVal(0);
        })->append(TagRelationshipModel::tableName(), function(Table $table) {
            $table->set('tag_id')->int()->notNull();
            $table->set('blog_id')->int()->notNull();
            $table->set('position')->tinyint(3)->defaultVal(99);
        });
        parent::up();
    }
}