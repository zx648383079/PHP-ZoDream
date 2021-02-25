<?php
namespace Module\Short\Domain\Migrations;

use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateShortTables extends Migration {

    public function up() {
        $this->append(ShortUrlModel::tableName(), function (Table $table) {
            $table->comment('短链接');
            $table->id();
            $table->uint('user_id');
            $table->column('short_url')->varchar(60)->comment('短链接');
            $table->column('source_url')->varchar(255)->comment('源网址');
            $table->uint('click_count')->default(0)->comment('点击次数');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->column('is_system')->bool()->default(0)->comment('是否未内部链接');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(ShortLogModel::tableName(), function (Table $table) {
            $table->comment('短链接访问记录');
            $table->id();
            $table->uint('short_id');
            $table->column('ip')->varchar(120)->comment('ip');
            $table->timestamp('created_at');
        })->autoUp();
    }
}