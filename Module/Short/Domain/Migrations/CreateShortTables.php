<?php
namespace Module\Short\Domain\Migrations;

use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateShortTables extends Migration {

    public function up() {
        $this->append(ShortUrlModel::tableName(), function (Table $table) {
            $table->setComment('短链接');
            $table->set('id')->pk(true);
            $table->set('user_id')->int(10)->unsigned();
            $table->set('short_url')->varchar(60)->notNull()->comment('短链接');
            $table->set('source_url')->varchar(255)->notNull()->comment('源网址');
            $table->set('click_count')->int()->defaultVal(0)->comment('点击次数');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->set('is_system')->bool()->defaultVal(0)->comment('是否未内部链接');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(ShortLogModel::tableName(), function (Table $table) {
            $table->setComment('短链接访问记录');
            $table->set('id')->pk(true);
            $table->set('short_id')->int(10)->unsigned();
            $table->set('ip')->varchar(120)->notNull()->comment('ip');
            $table->timestamp('created_at');
        })->autoUp();
    }
}