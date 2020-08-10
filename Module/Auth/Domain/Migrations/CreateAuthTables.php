<?php
namespace Module\Auth\Domain\Migrations;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\CreditLogModel;
use Module\Auth\Domain\Model\EquityCardModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\LoginQrModel;
use Module\Auth\Domain\Model\MailLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateAuthTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(UserModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('email')->varchar(200)->notNull();
            $table->set('password')->varchar(100)->notNull();
            $table->set('sex')->tinyint(1)->defaultVal(0);
            $table->set('avatar')->varchar(255);
            $table->set('birthday')->date()->defaultVal(date('Y-m-d'));
            $table->set('money')->int()->defaultVal(0)->unsigned();
            $table->set('credits')->int()->defaultVal(0)->unsigned()->defaultVal('积分');
            $table->set('parent_id')->int(10, true, true)->defaultVal(0);
            $table->set('token')->varchar(60);
            $table->set('status')->tinyint(2)->defaultVal(UserModel::STATUS_ACTIVE);
            $table->timestamps();
        })->append(OAuthModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('platform_id')->int()->defaultVal(0)->comment('平台id');
            $table->set('nickname')->varchar(30)->defaultVal('')->comment('昵称');
            $table->set('vendor')->varchar(20)->defaultVal(OAuthModel::TYPE_QQ);
            $table->set('unionid')->varchar(100)->defaultVal('联合id');
            $table->set('identity')->varchar(100)->notNull();
            $table->set('data')->text();
            $table->timestamp('created_at');
        })->append(UserMetaModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(100)->notNull();
            $table->set('content')->text()->notNull();
        })->append(LoginQrModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('token')->varchar(32)->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(EquityCardModel::tableName(), function(Table $table) {
            $table->setComment('有期限的权益卡');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('token')->varchar(32)->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamp('expired_at');
            $table->timestamps();
        });
        $this->createLog();
        $this->createRole();
        $this->createBulletin();
        parent::up();
    }

    public function seed() {
        RoleRepository::newRole('administrator', '超级管理员');
    }

    public function createRole(): void {
        $this->append(RoleModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull()->unique();
            $table->set('display_name')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->timestamps();
        })->append(UserRoleModel::tableName(), function (Table $table) {
            $table->set('user_id')->int()->notNull()->unsigned();
            $table->set('role_id')->int()->notNull()->unsigned();
        })->append(PermissionModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(40)->notNull()->unique();
            $table->set('display_name')->varchar(100)->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->timestamps();
        })->append(RolePermissionModel::tableName(), function (Table $table) {
            $table->set('role_id')->int()->notNull()->unsigned();
            $table->set('permission_id')->int()->notNull()->unsigned();
        });
    }

    public function createBulletin(): void {
        $this->append(BulletinModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(100)->notNull();
            $table->set('content')->varchar()->notNull();
            $table->set('type')->tinyint(2)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->timestamps();
        })->append(BulletinUserModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('bulletin_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('user_id')->int()->notNull();
            $table->timestamps();
        });
    }

    public function createLog() {
        $this->append(AccountLogModel::tableName(), function (Table $table) {
            $table->setComment('账户资金变动表');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->unsigned()->defaultVal(0);
            $table->set('type')->tinyint(1)->unsigned()->defaultVal(99);
            $table->set('item_id')->int()->defaultVal(0);
            $table->set('money')->int()->notNull()->comment('本次发生金额');
            $table->set('total_money')->int()->notNull()->comment('当前账户余额');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('remark')->varchar()->notNull();
            $table->timestamps();
        })->append(CreditLogModel::tableName(), function (Table $table) {
            $table->setComment('账户积分变动表');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->unsigned()->defaultVal(0);
            $table->set('type')->tinyint(1)->unsigned()->defaultVal(99);
            $table->set('item_id')->int()->defaultVal(0);
            $table->set('credits')->int()->notNull()->comment('本次发生积分');
            $table->set('total_credits')->int()->notNull()->comment('当前账户积分');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('remark')->varchar()->notNull();
            $table->timestamps();
        })->append(LoginLogModel::tableName(), function (Table $table) {
            $table->setComment('账户登录日志表');
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('user')->varchar(100)->notNull()->comment('登陆账户');
            $table->set('status')->bool()->defaultVal(0);
            $table->set('mode')->varchar(20)->defaultVal(LoginLogModel::MODE_WEB);
            $table->timestamp('created_at');
        })->append(ActionLogModel::tableName(), function (Table $table) {
            $table->setComment('操作记录');
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('action')->varchar(30)->notNull();
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        })->append(MailLogModel::tableName(), function (Table $table) {
            $table->setComment('发送邮件记录');
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('code')->varchar(40)->notNull();
            $table->set('amount')->tinyint(1)->defaultVal(10);
            $table->timestamp('created_at');
        });
    }
}