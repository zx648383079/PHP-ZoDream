<?php
namespace Module\Contact\Domain\Migrations;

use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\SubscribeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;


class CreateContactTables extends Migration {

    public function up() {
        $this->append(FeedbackModel::tableName(), function (Table $table) {
            $table->comment('留言');
            $table->id();
            $table->string('name', 20);
            $table->string('email', 50)->default('');
            $table->string('phone', 30)->default('');
            $table->string('content')->default('');
            $table->bool('status')->default(0);
            $table->uint('user_id')->default(0);
            $table->timestamps();
        })->append(FriendLinkModel::tableName(), function (Table $table) {
            $table->comment('友情链接');
            $table->id();
            $table->string('name', 20);
            $table->string('url', 50);
            $table->string('logo', 200)->default('');
            $table->string('brief')->default('');
            $table->string('email', 100)->default('');
            $table->bool('status')->default(0);
            $table->uint('user_id')->default(0);
            $table->timestamps();
        })->append(SubscribeModel::tableName(), function (Table $table) {
            $table->comment('邮箱订阅');
            $table->id();
            $table->string('email', 50);
            $table->bool('status')->default(0);
            $table->timestamps();
        })->autoUp();
    }
}