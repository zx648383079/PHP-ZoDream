<?php
declare(strict_types=1);
namespace Module\Short\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateShortTables extends Migration {

    public function up(): void {
        $this->append(ShortUrlModel::tableName(), function (Table $table) {
            $table->comment('短链接');
            $table->id();
            $table->uint('user_id');
            $table->string('title', 30)->default('unknown')->comment('备注/标题');
            $table->string('short_url', 60)->comment('短链接');
            $table->string('source_url', 255)->comment('源网址');
            $table->uint('click_count')->default(0)->comment('点击次数');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->bool('is_system')->default(0)->comment('是否未内部链接');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(ShortLogModel::tableName(), function (Table $table) {
            $table->comment('短链接访问记录');
            $table->id();
            $table->uint('short_id');
            $table->string('ip', 120)->comment('ip');
            $table->timestamp('created_at');
        })->autoUp();
    }

    public function seed(): void {
        RoleRepository::newPermission([
            'short_link_manage' => '短链接管理'
        ]);
    }
}