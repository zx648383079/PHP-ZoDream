<?php
namespace Module\OpenPlatform\Domain\Migrations;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateOpenPlatformTables extends Migration {

    public function up() {
        $this->append(PlatformModel::tableName(), function (Table $table) {
            $table->comment('第三方授权信息');
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(20);
            $table->column('description')->varchar()->default('')->comment('说明');
            $table->uint('type', 1)->default(0);
            $table->column('domain')->varchar(50);
            $table->column('appid')->char(12)->unique();
            $table->column('secret')->char(32);
            $table->uint('sign_type', 1)->default(0)->comment('签名方式');
            $table->column('sign_key')->varchar(32)->comment('签名密钥');
            $table->uint('encrypt_type', 1)->default(0)->comment('加密方式');
            $table->column('public_key')->text()->nullable()->comment('密钥');
            $table->column('rules')->varchar()->default('')->comment('允许访问的网址');
            $table->uint('status', 1)->default(0);
            $table->column('allow_self')->bool()->default(0)->comment('是否允许后台用户自己添加');
            $table->timestamps();
        })->append(UserTokenModel::tableName(), function (Table $table) {
            $table->comment('用户授权平台令牌');
            $table->id();
            $table->uint('user_id');
            $table->uint('platform_id');
            $table->column('token')->text();
            $table->column('is_self')->bool()->default(0)->comment('是否时用户后台添加的');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(PlatformOptionModel::tableName(), function (Table $table) {
            $table->comment('平台一些第三方接口配置');
            $table->id();
            $table->uint('platform_id');
            $table->column('store')->varchar(20)->comment('平台别名');
            $table->column('name')->varchar(30)->comment('字段');
            $table->column('value')->text()->nullable()->comment('配置值');
            $table->timestamps();
        })->autoUp();
    }
}