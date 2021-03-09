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
            $table->uint('type', 1)->default(0)->comment('签到类型，1为补签');
            $table->uint('running', 5)->default(1)->comment('连续几天');
            $table->uint('money', 5)->default(0)->comment('虚拟币奖励');
            $table->string('ip', 120)->default('');
            $table->uint('method', 2)->default(0)->comment('签到方式');
            $table->timestamp('created_at');
        })->autoUp();
    }
}