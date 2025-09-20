<?php
namespace Module\Auth\Domain\Migrations;

use Module\Auth\Domain\Entities\InviteCodeEntity;
use Module\Auth\Domain\Entities\InviteLogEntity;
use Module\Auth\Domain\Entities\UserEntity;
use Module\Auth\Domain\Entities\UserRelationshipEntity;
use Module\Auth\Domain\Entities\ZoneEntity;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\AdminLogModel;
use Module\Auth\Domain\Model\ApplyLogModel;
use Module\Auth\Domain\Model\BanAccountModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\Card\EquityCardModel;
use Module\Auth\Domain\Model\Card\UserEquityCardModel;
use Module\Auth\Domain\Model\CreditLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\InviteRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\SEO\Domain\Option;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Schema\Table;

class CreateAuthTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        $this->append(UserEntity::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 200)->default('');
            $table->string('mobile', 20)->default('');
            $table->string('password', 100);
            $table->uint('sex', 1)->default(0);
            $table->string('avatar')->default('');
            $table->date('birthday')->default(date('Y-m-d'));
            $table->uint('money')->default(0);
            $table->uint('credits')->default(0)->comment('积分');
            $table->uint('parent_id')->default(0);
            $table->string('token', 60)->default(0);
            $table->uint('status', 2)->default(UserModel::STATUS_ACTIVE)->comment('账户的是否有限状态');
            $table->timestamp('activated_at')->comment('最后活跃的时间');
            $table->timestamps();
        })->append(OAuthModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('platform_id')->default(0)->comment('平台id');
            $table->string('nickname', 30)->default('')->comment('昵称');
            $table->string('vendor', 20)->default(OAuthModel::TYPE_QQ);
            $table->string('unionid', 100)->default('')->comment('联合id');
            $table->string('identity', 100);
            $table->text('data')->nullable();
            $table->timestamp(Model::CREATED_AT);
        })->append(UserMetaModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('name', 100);
            $table->text('content');
        })->append(UserRelationshipEntity::tableName(), function(Table $table) {
            $table->comment('用户关系表');
            $table->uint('user_id');
            $table->uint('link_id')->comment('被联系的人');
            $table->uint('type', 1)->default(0)->comment('具体关系');
            $table->timestamp(Model::CREATED_AT);
        })->append(BanAccountModel::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id')->default(0);
            $table->string('item_key', 100);
            $table->uint('item_type', 2)->default(0);
            $table->uint('platform_id')->default(0)->comment('平台id');
            $table->timestamps();
        })->append(EquityCardModel::tableName(), function(Table $table) {
            $table->comment('有期限的权益卡');
            $table->id();
            $table->string('name', 32);
            $table->string('icon');
            $table->string('configure', 200)->default('');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(UserEquityCardModel::tableName(), function(Table $table) {
            $table->comment('用户的权益卡');
            $table->id();
            $table->uint('user_id');
            $table->uint('card_id');
            $table->uint('exp')->default(0);
            $table->uint('status', 2)->default(0);
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(InviteCodeEntity::tableName(), function(Table $table) {
            $table->comment('邀请码生成');
            $table->id();
            $table->uint('type', 1)->default(InviteRepository::TYPE_CODE);
            $table->uint('user_id')->default(0);
            $table->uint('amount')->default(1);
            $table->uint('invite_count')->default(0);
            $table->string('token', 32);
            $table->timestamp('expired_at');
            $table->timestamps();
        })->append(InviteLogEntity::tableName(), function(Table $table) {
            $table->comment('邀请记录');
            $table->id();
            $table->uint('user_id');
            $table->uint('parent_id')->default(0);
            $table->uint('code_id')->default(0);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(ZoneEntity::tableName(), function(Table $table) {
            $table->comment('分区');
            $table->id();
            $table->string('name', 30);
            $table->string('icon')->default('');
            $table->string('description')->default('');
            $table->bool('is_open')->default(0);
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        });
        $this->createLog();
        $this->createRole();
        $this->createBulletin();
        parent::up();
    }

    public function seed(): void {
        RoleRepository::newRole('administrator', '超级管理员');
        RoleRepository::newPermission([
            'user_manage' => '会员管理'
        ]);
        RoleRepository::newPermission([
            'system_manage' => '系统配置'
        ]);
        Option::group('高级', function () {
            return [
                [
                    'name' => '注册方式',
                    'code' => AuthRepository::OPTION_REGISTER_CODE,
                    'type' => 'radio',
                    'value' => 0,
                    'default_value' => "默认注册\n邀请码注册\n关闭注册",
                    'visibility' => 2,
                ],
                [
                    'name' => '开启第三登录',
                    'code' => AuthRepository::OPTION_OAUTH_CODE,
                    'type' => 'switch',
                    'value' => 0,
                    'visibility' => 2,
                ],
            ];
        });
    }

    public function createRole(): void {
        $this->append(RoleModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('display_name', 100)->default('');
            $table->string('description')->default('');
            $table->timestamps();
        })->append(UserRoleModel::tableName(), function (Table $table) {
            $table->uint('user_id');
            $table->uint('role_id');
        })->append(PermissionModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('display_name', 100)->default('');
            $table->string('description')->default('');
            $table->timestamps();
        })->append(RolePermissionModel::tableName(), function (Table $table) {
            $table->uint('role_id');
            $table->uint('permission_id');
        });
    }

    public function createBulletin(): void {
        $this->append(BulletinModel::tableName(), function (Table $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('content');
            $table->string('extra_rule')->default('');
            $table->uint('type', 2)->default(0);
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
            $table->uint('type', 1)->default(99);
            $table->uint('item_id')->default(0);
            $table->int('money')->comment('本次发生金额');
            $table->int('total_money')->comment('当前账户余额');
            $table->uint('status', 2)->default(0);
            $table->string('remark')->default('');
            $table->timestamps();
        })->append(CreditLogModel::tableName(), function (Table $table) {
            $table->comment('账户积分变动表');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->uint('type', 1)->default(99);
            $table->uint('item_id')->default(0);
            $table->int('credits')->comment('本次发生积分');
            $table->int('total_credits')->comment('当前账户积分');
            $table->uint('status', 2)->default(0);
            $table->string('remark')->default('');
            $table->timestamps();
        })->append(LoginLogModel::tableName(), function (Table $table) {
            $table->comment('账户登录日志表');
            $table->id();
            $table->string('ip', 120);
            $table->uint('user_id')->default(0);
            $table->string('user', 100)->comment('登陆账户');
            $table->bool('status')->default(0);
            $table->string('mode', 20)->default(LoginLogModel::MODE_WEB);
            $table->uint('platform_id')->default(0)->comment('平台id');
            $table->timestamp(Model::CREATED_AT);
        })->append(ActionLogModel::tableName(), function (Table $table) {
            $table->comment('操作记录');
            $table->id();
            $table->string('ip', 120);
            $table->uint('user_id');
            $table->string('action', 30);
            $table->string('remark')->default('');
            $table->timestamp(Model::CREATED_AT);
        })->append(AdminLogModel::tableName(), function (Table $table) {
            $table->comment('管理员操作记录');
            $table->id();
            $table->string('ip', 120);
            $table->uint('user_id');
            $table->uint('item_type', 2)->default(0);
            $table->uint('item_id')->default(0);
            $table->string('action', 30);
            $table->string('remark')->default('');
            $table->timestamp(Model::CREATED_AT);
        })->append(ApplyLogModel::tableName(), function (Table $table) {
            $table->comment('用户申请记录');
            $table->id();
            $table->uint('user_id');
            $table->uint('type', 1)->default(0);
            $table->int('money')->default(0);
            $table->string('remark')->default('');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        });
    }
}