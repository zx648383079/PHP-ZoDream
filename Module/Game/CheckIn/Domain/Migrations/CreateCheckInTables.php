<?php
namespace Module\Game\CheckIn\Domain\Migrations;


use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCheckInTables extends Migration {
    public function up() {
        $this->append(CheckInModel::tableName(), function(Table $table) {
            $table->comment('签到记录');
            $table->id();
            $table->uint('user_id');
            $table->column('type')->tinyint(1)->unsigned()->default(0)->comment('签到类型，1为补签');
            $table->column('running')->int(5)->unsigned()->default(1)->comment('连续几天');
            $table->column('money')->short(5)->unsigned()->default(0)->comment('虚拟币奖励');
            $table->column('ip')->varchar(120)->default('');
            $table->column('method')->tinyint(2)->unsigned()->default(0)->comment('签到方式');
            $table->timestamp('created_at');
        })->autoUp();
    }
}