<?php
namespace Module\Chat\Domain\Migrations;

use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\FriendGroupModel;
use Module\Chat\Domain\Model\MessageModel;
use Module\Chat\Domain\Model\FriendModel;
use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateChatTables extends Migration {

    public function up() {
        $this->append(FriendModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('备注');
            $table->set('group_id')->int()->notNull()->comment('分组');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->set('belong_id')->int()->notNull()->comment('归属');
            $table->timestamps();
        })->append(ApplyModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('group_id')->int()->notNull()->comment('目标分组');
            $table->set('user_id')->int()->notNull()->comment('目标用户');
            $table->set('remark')->varchar()->defaultVal('');
            $table->set('apply_user')->int()->notNull()->comment('申请人');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(FriendGroupModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('分组名');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamp('created_at');
        })->append(MessageModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('type')->tinyint(2)->notNull()->defaultVal(0);
            $table->set('content')->varchar(200)->notNull()->comment('内容');
            $table->set('receive_id')->int()->notNull()
                ->defaultVal(0)->comment('接收用户');
            $table->set('group_id')->int()->notNull()
                ->defaultVal(0)->comment('所属群');
            $table->set('user_id')->int()->notNull()->comment('发送用户');
            $table->set('status')->tinyint(1)->notNull()->defaultVal(0);
            $table->softDeletes();
            $table->timestamps();
        })->append(GroupModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull()->comment('群名');
            $table->set('logo')->varchar(100)->notNull()->comment('群LOGO');
            $table->set('description')->varchar(100)->notNull()->defaultVal('')
                ->comment('群说明');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->timestamps();
        })->append(GroupUserModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('group_id')->int()->notNull()->comment('群');
            $table->set('user_id')->int()->notNull()->comment('用户');
            $table->set('name')->varchar(100)->notNull()->comment('群备注');
            $table->set('role_id')->int()->notNull()->defaultVal(0)->comment('管理员等级');
            $table->timestamps();
        });
        parent::up();
    }
}