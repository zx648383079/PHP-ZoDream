<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Migrations;

use Module\AdSense\Domain\Entities\AdEntity;
use Module\AdSense\Domain\Entities\AdPositionEntity;
use Module\AdSense\Domain\Repositories\AdRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Database\Contracts\Table;
use Zodream\Database\Migrations\Migration;

class CreateAdTables extends Migration {

    public function up(): void {
        $this->append(AdEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->uint('position_id');
            $table->uint('type', 1)->default(AdRepository::TYPE_TEXT);
            $table->string('url');
            $table->string('content');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->bool('status')->default(1)->comment('是否启用广告');
            $table->timestamps();
        })->append(AdPositionEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 20)->unique()->comment('调用广告位的代码');
            $table->bool('auto_size')->default(1)->comment('自适应');
            $table->bool('source_type')->default(0)->comment('广告来源');
            $table->string('width', 10)->default('');
            $table->string('height', 10)->default('');
            $table->string('template', 500)->default('');
            $table->bool('status')->default(1)->comment('是否启用广告位');
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'ad_manage' => '广告管理'
        ]);
        if (AdPositionEntity::query()->count() > 0) {
            return;
        }
        $data = [
            'banner' => '首页Banner',
            'mobile_banner' => '手机端首页Banner',
            'home_notice' => '首页通知栏',
            'home_floor' => '首页楼层',
            'app_list' => 'APP列表页',
            'app_detail' => 'APP详情页',
            'blog_list' => 'Blog列表页',
            'blog_detail' => 'Blog详情页',
            'res_list' => '资源列表页',
            'res_detail' => '资源详情页',
            'cms_article' => 'CMS文章详情页',
            'bbs_list' => '论坛列表页',
            'bbs_thread' => '论坛帖子页',
            'book_chapter' => '小说章节页',
            'book_detail' => '小说详情页',
        ];
        $items = [];
        foreach ($data as $code => $name) {
            $items[] = [
                'name' => $name,
                'code' => $code,
                'template' => '{each}{.content}{/each}',
            ];
        }
        AdPositionEntity::query()->insert($items);
    }
}