<?php
namespace Module\Game\CheckIn\Domain\Migrations;


use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCheckInTables extends Migration {
    public function up() {
        $this->append(CheckInModel::tableName(), function(Table $table) {
            $table->setComment('签到记录');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('签到类型，1为补签');
            $table->set('running')->int(5)->defaultVal(1)->comment('连续几天');
            $table->set('money')->int(5)->defaultVal(0)->comment('虚拟币奖励');
            $table->set('ip')->varchar(120)->defaultVal('');
            $table->set('method')->tinyint(2)->defaultVal(0)->comment('签到方式');
            $table->timestamp('created_at');
        })->append('');
        parent::up();
    }
}