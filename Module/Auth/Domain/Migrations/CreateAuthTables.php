<?php
namespace Module\Auth\Domain\Migrations;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\LoginQrModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\PermissionModel;
use Module\Auth\Domain\Model\RoleModel;
use Module\Auth\Domain\Model\RolePermissionModel;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\UserRoleModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateAuthTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(UserModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('email')->varchar(200)->notNull();
            $table->set('password')->varchar(100)->notNull();
            $table->set('sex')->tinyint(1)->defaultVal(0);
            $table->set('avatar')->varchar(255);
            $table->set('money')->int()->defaultVal(0)->unsigned();
            $table->set('token')->varchar(60);
            $table->set('status')->tinyint(2)->defaultVal(UserModel::STATUS_ACTIVE);
            $table->timestamps();
        });
        Schema::createTable(OAuthModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('vendor')->varchar(20)->defaultVal('qq');
            $table->set('identity')->varchar(100)->notNull();
            $table->set('data')->text();
            $table->timestamp('created_at');
        });
        Schema::createTable(LoginLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('user')->varchar(100)->notNull()->comment('登陆账户');
            $table->set('status')->bool()->defaultVal(0);
            $table->set('mode')->varchar(20)->defaultVal(LoginLogModel::MODE_WEB);
            $table->timestamp('created_at');
        });
        Schema::createTable(ActionLogModel::tableName(), function(Table $table) {
            $table->setComment('操作记录 ');
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('action')->varchar(30)->notNull();
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        });
        Schema::createTable(UserMetaModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull();
            $table->set('content')->text()->notNull();
        });
        Schema::createTable(LoginQrModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('token')->varchar(32)->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamp('expired_at');
            $table->timestamps();
        });
        Schema::createTable(AccountLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->unsigned()->defaultVal(0);
            $table->set('type')->tinyint(2)->unsigned()->defaultVal(99);
            $table->set('item_id')->int()->defaultVal(0);
            $table->set('money')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('remark')->varchar()->notNull();
            $table->timestamps();
        });
        $this->createRole();
        $this->createBulletin();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(UserModel::tableName());
        Schema::dropTable(OAuthModel::tableName());
        Schema::dropTable(LoginLogModel::tableName());
        Schema::dropTable(UserMetaModel::tableName());
        Schema::dropTable(LoginQrModel::tableName());
    }

    public function createRole(): void {
        Schema::createTable(RoleModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull()->unique();
            $table->set('display_name')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->timestamps();
        });
        Schema::createTable(UserRoleModel::tableName(), function (Table $table) {
            $table->set('user_id')->int()->notNull()->unsigned();
            $table->set('role_id')->int()->notNull()->unsigned();
        });
        Schema::createTable(PermissionModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull()->unique();
            $table->set('display_name')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->timestamps();
        });
        Schema::createTable(RolePermissionModel::tableName(), function (Table $table) {
            $table->set('role_id')->int()->notNull()->unsigned();
            $table->set('permission_id')->int()->notNull()->unsigned();
        });
    }

    public function createBulletin(): void {
        Schema::createTable(BulletinModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(100)->notNull();
            $table->set('content')->varchar()->notNull();
            $table->set('type')->tinyint(2)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->timestamps();
        });
        Schema::createTable(BulletinUserModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('bulletin_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->timestamps();
        });
    }
}