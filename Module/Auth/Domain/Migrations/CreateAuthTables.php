<?php
namespace Module\Auth\Domain\Migrations;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\AdminLogModel;
use Module\Auth\Domain\Model\ApplyLogModel;
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
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('email')->varchar(200);
            $table->column('password')->varchar(100);
            $table->uint('sex', 1)->default(0);
            $table->column('avatar')->varchar(255)->default('');
            $table->column('birthday')->date()->default(date('Y-m-d'));
            $table->uint('money')->default(0);
            $table->uint('credits')->default(0)->default('积分');
            $table->uint('parent_id')->default(0);
            $table->column('token')->varchar(60)->default(0);
            $table->uint('status', 2)->default(UserModel::STATUS_ACTIVE);
            $table->timestamps();
        })->append(OAuthModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('platform_id')->default(0)->comment('平台id');
            $table->column('nickname')->varchar(30)->default('')->comment('昵称');
            $table->column('vendor')->varchar(20)->default(OAuthModel::TYPE_QQ);
            $table->column('unionid')->varchar(100)->default('联合id');
            $table->column('identity')->varchar(100);
            $table->column('data')->nullable()->text();
            $table->timestamp('created_at');
        })->append(UserMetaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(100);
            $table->column('content')->text();
        })->append(LoginQrModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id')->default(0);
            $table->column('token')->varchar(32);
            $table->uint('status', 2)->default(0);
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(EquityCardModel::tableName(), function(Table $table) {
            $table->comment('有期限的权益卡');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->column('token')->varchar(32);
            $table->uint('status', 2)->default(0);
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
            $table->id();
            $table->column('name')->varchar(40)->unique();
            $table->column('display_name')->varchar(100)->default('');
            $table->column('description')->varchar()->default('');
            $table->timestamps();
        })->append(UserRoleModel::tableName(), function (Table $table) {
            $table->uint('user_id');
            $table->uint('role_id');
        })->append(PermissionModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar(40)->unique();
            $table->column('display_name')->varchar(100)->default('');
            $table->column('description')->varchar()->default('');
            $table->timestamps();
        })->append(RolePermissionModel::tableName(), function (Table $table) {
            $table->uint('role_id');
            $table->uint('permission_id');
        });
    }

    public function createBulletin(): void {
        $this->append(BulletinModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('title')->varchar(100);
            $table->column('content')->varchar();
            $table->column('type')->tinyint(2)->default(0);
            $table->uint('user_id');
            $table->timestamps();
        })->append(BulletinUserModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('bulletin_id');
            $table->uint('status', 2)->default(0);
            $table->uint('user_id');
            $table->timestamps();
        });
    }

    public function createLog() {
        $this->append(AccountLogModel::tableName(), function (Table $table) {
            $table->comment('账户资金变动表');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->column('type')->tinyint(1)->unsigned()->default(99);
            $table->column('item_id')->int()->default(0);
            $table->column('money')->int()->comment('本次发生金额');
            $table->column('total_money')->int()->comment('当前账户余额');
            $table->uint('status', 2)->default(0);
            $table->column('remark')->varchar();
            $table->timestamps();
        })->append(CreditLogModel::tableName(), function (Table $table) {
            $table->comment('账户积分变动表');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->column('type')->tinyint(1)->unsigned()->default(99);
            $table->column('item_id')->int()->default(0);
            $table->column('credits')->int()->comment('本次发生积分');
            $table->column('total_credits')->int()->comment('当前账户积分');
            $table->uint('status', 2)->default(0);
            $table->column('remark')->varchar();
            $table->timestamps();
        })->append(LoginLogModel::tableName(), function (Table $table) {
            $table->comment('账户登录日志表');
            $table->id();
            $table->column('ip')->varchar(120);
            $table->uint('user_id')->default(0);
            $table->column('user')->varchar(100)->comment('登陆账户');
            $table->column('status')->bool()->default(0);
            $table->column('mode')->varchar(20)->default(LoginLogModel::MODE_WEB);
            $table->timestamp('created_at');
        })->append(ActionLogModel::tableName(), function (Table $table) {
            $table->comment('操作记录');
            $table->id();
            $table->column('ip')->varchar(120);
            $table->uint('user_id');
            $table->column('action')->varchar(30);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        })->append(AdminLogModel::tableName(), function (Table $table) {
            $table->comment('管理员操作记录');
            $table->id();
            $table->column('ip')->varchar(120);
            $table->uint('user_id');
            $table->column('item_type')->tinyint()->default(0);
            $table->column('item_id')->int()->default(0);
            $table->column('action')->varchar(30);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        })->append(ApplyLogModel::tableName(), function (Table $table) {
            $table->comment('用户申请记录');
            $table->id();
            $table->uint('user_id');
            $table->column('type')->tinyint(1)->default(0);
            $table->column('money')->int()->default(0);
            $table->column('remark')->varchar()->default('');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(MailLogModel::tableName(), function (Table $table) {
            $table->comment('发送邮件记录');
            $table->id();
            $table->column('ip')->varchar(120);
            $table->uint('user_id');
            $table->column('type')->tinyint(1)->default(0);
            $table->column('code')->varchar(40);
            $table->column('amount')->tinyint(1)->default(10);
            $table->timestamp('created_at');
        });
    }
}