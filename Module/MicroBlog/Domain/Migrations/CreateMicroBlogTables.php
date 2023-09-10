<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\BlogTopicModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Model\TopicModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\SEO\Domain\Option;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateMicroBlogTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        MicroRepository::comment()->migration($this);
        $this->append(MicroBlogModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('content', 140);
            $table->string('extra_rule', 500)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('open_type', 1)->default(0);
            $table->uint('recommend_count')->default(0)->comment('推荐数');
            $table->uint('collect_count')->default(0)->comment('收藏数');
            $table->uint('forward_count')->default(0)->comment('转发数');
            $table->uint('comment_count')->default(0)->comment('评论数');
            $table->uint('forward_id')->default(0)->comment('转发的源id');
            $table->string('source', 30)->default('')->comment('来源');
            $table->timestamps();
        })->append(AttachmentModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->string('thumb');
            $table->string('file');
        })->append(TopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 200);
            $table->uint('user_id');
            $table->timestamps();
        })->append(BlogTopicModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('micro_id');
            $table->uint('topic_id');
        })->autoUp();
    }

    public function seed(): void
    {
        RoleRepository::newPermission([
            'micro_manage' => '微博管理'
        ]);
        Option::group('微博客设置', function () {
            return [
                [
                    'name' => '发布间隔/秒',
                    'code' => 'micro_time_limit',
                    'type' => 'text',
                    'value' => 300,
                    'visibility' => 2,
                ],
            ];
        });
    }
}