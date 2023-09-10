<?php
namespace Module\Chat\Domain\Migrations;

use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\FriendClassifyModel;
use Module\Chat\Domain\Model\MessageModel;
use Module\Chat\Domain\Model\FriendModel;
use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateChatTables extends Migration {

    public function up(): void {
        $this->append(FriendModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 50)
                ->default('')->comment('备注');
            $table->uint('classify_id')->default(1)->comment('分组/1为默认分组，0为黑名单');
            $table->uint('user_id')->comment('用户');
            $table->uint('belong_id')->comment('归属');
            $table->bool('status')->default(0)->comment('是否互相关注');
            $table->timestamps();
        })->append(ApplyModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 2)->default(0)->comment('申请类别');
            $table->uint('item_id')->comment('申请内容');
            $table->string('remark')->default('');
            $table->uint('user_id')->comment('申请人');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(FriendClassifyModel::tableName(), function(Table $table) {
            $table->id()->ai(10);
            $table->string('name', 100)->comment('分组名');
            $table->uint('user_id')->comment('用户');
            $table->timestamp('created_at');
        })->append(MessageModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('type', 2)->default(0);
            $table->string('content', 400)->comment('内容');
            $table->string('extra_rule', 400)
                ->default('')->comment('附加替换规则');
            $table->uint('item_id')
                ->default(0)->comment('附加id');
            $table->uint('receive_id')
                ->default(0)->comment('接收用户');
            $table->uint('group_id')
                ->default(0)->comment('所属群');
            $table->uint('user_id')->comment('发送用户');
            $table->uint('status', 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(GroupModel::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 50)->comment('群名');
            $table->string('logo', 100)->comment('群LOGO');
            $table->string('description')->default('')
                ->comment('群说明');
            $table->uint('user_id')->comment('用户');
            $table->timestamps();
        })->append(GroupUserModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('group_id')->comment('群');
            $table->uint('user_id')->comment('用户');
            $table->string('name', 50)
                ->default('')->comment('群备注');
            $table->uint('role_id')->default(0)->comment('管理员等级');
            $table->uint('status', 1)
                ->default(5)->comment('用户状态/禁言或');
            $table->timestamps();
        })->append(ChatHistoryModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('item_type', 2);
            $table->uint('item_id')->comment('聊天历史');
            $table->uint('user_id')->comment('关联用户');
            $table->uint('unread_count')->comment('未读消息数量');
            $table->uint('last_message')->comment('最后一条消息');
            $table->timestamps();
        })->autoUp();
    }
}