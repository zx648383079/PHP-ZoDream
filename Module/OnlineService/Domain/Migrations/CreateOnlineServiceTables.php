<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Migrations;


use Module\OnlineService\Domain\Models\CategoryModel;
use Module\OnlineService\Domain\Models\CategoryUserModel;
use Module\OnlineService\Domain\Models\CategoryWordModel;
use Module\OnlineService\Domain\Models\MessageModel;
use Module\OnlineService\Domain\Models\SessionLogModel;
use Module\OnlineService\Domain\Models\SessionModel;
use Zodream\Database\Contracts\Table;
use Zodream\Database\Migrations\Migration;

class CreateOnlineServiceTables extends Migration {
    public function up() {
        $this->append(CategoryModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name');
        })->append(CategoryWordModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('content');
            $table->uint('cat_id');
        })->append(CategoryUserModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->uint('user_id');
            $table->string('auth_reply')->default('');
            $table->timestamps();
        })->append(SessionModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 20)->default('');
            $table->string('remark')->default('');
            $table->uint('user_id')->default(0);
            $table->uint('service_id')->default(0);
            $table->string('ip', 120);
            $table->string('user_agent', 255);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(SessionLogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('session_id');
            $table->string('remark')->default('');
            $table->uint('status', 1)->default(0);
            $table->timestamp('created_at');
        })->append(MessageModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id')->default(0)->comment('发送者');
            $table->uint('session_id');
            $table->bool('send_type')->default(0)->comment('发送者的身份，0咨询者1客服');
            $table->uint('type', 2)->default(0)->comment('内容类型');
            $table->string('content')->default('');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->autoUp();
    }
}
