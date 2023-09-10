<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain\Migrations;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateOpenPlatformTables extends Migration {

    public function up(): void {
        $this->append(PlatformModel::tableName(), function (Table $table) {
            $table->comment('第三方授权信息');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 20);
            $table->string('description')->default('')->comment('说明');
            $table->uint('type', 1)->default(0);
            $table->string('domain', 50);
            $table->string('appid', 12)->unique();
            $table->char('secret', 32);
            $table->uint('sign_type', 1)->default(0)->comment('签名方式');
            $table->string('sign_key', 32)->default('')->comment('签名密钥');
            $table->uint('encrypt_type', 1)->default(0)->comment('加密方式');
            $table->text('public_key')->nullable()->comment('密钥');
            $table->string('rules')->default('')->comment('允许访问的网址');
            $table->uint('status', 1)->default(0);
            $table->bool('allow_self')->default(0)->comment('是否允许后台用户自己添加');
            $table->timestamps();
        })->append(UserTokenModel::tableName(), function (Table $table) {
            $table->comment('用户授权平台令牌');
            $table->id();
            $table->uint('user_id');
            $table->uint('platform_id');
            $table->text('token');
            $table->bool('is_self')->default(0)->comment('是否时用户后台添加的');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(PlatformOptionModel::tableName(), function (Table $table) {
            $table->comment('平台一些第三方接口配置');
            $table->id();
            $table->uint('platform_id');
            $table->string('store', 20)->comment('平台别名');
            $table->string('name', 30)->comment('字段');
            $table->text('value')->nullable()->comment('配置值');
            $table->timestamps();
        })->autoUp();
    }

    public function seed(): void
    {
        RoleRepository::newPermission([
            'open_manage' => '开放应用管理'
        ]);
    }
}