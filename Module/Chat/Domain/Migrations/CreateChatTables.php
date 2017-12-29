<?php
namespace Module\Chat\Domain\Migrations;

use Module\Chat\Domain\Model\FriendGroupModel;
use Module\Chat\Domain\Model\FriendMessageModel;
use Module\Chat\Domain\Model\FriendModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateChatTables extends Migration {

    public function up() {
        Schema::createTable(FriendModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('备注');
            $table->set('group_id')->int()->notNull()->comment('分组');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamp('created_at');
        });
        Schema::createTable(FriendMessageModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(2)->notNull()->defaultVal(0);
            $table->set('content')->varchar(200)->notNull()->comment('内容');
            $table->set('receive_id')->int()->notNull()->comment('接收用户');
            $table->set('user_id')->int()->notNull()->comment('发送用户');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(FriendGroupModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分组名');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamp('created_at');
        });
    }

    public function down() {
        Schema::dropTable(FriendModel::tableName());
        Schema::dropTable(FriendMessageModel::tableName());
        Schema::dropTable(FriendGroupModel::tableName());
    }
}