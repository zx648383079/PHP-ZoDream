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
            $table->setComment('第三方授权信息');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(20)->notNull();
            $table->set('description')->varchar()->defaultVal('')->comment('说明');
            $table->set('type')->tinyint(1)->notNull()->defaultVal(0);
            $table->set('domain')->varchar(50)->notNull();
            $table->set('appid')->char(12)->unique()->notNull();
            $table->set('secret')->char(32)->notNull();
            $table->set('sign_type')->tinyint(1)->defaultVal(0)->comment('签名方式');
            $table->set('sign_key')->varchar(32)->comment('签名密钥');
            $table->set('encrypt_type')->tinyint(1)->defaultVal(0)->comment('加密方式');
            $table->set('public_key')->text()->comment('密钥');
            $table->set('rules')->varchar()->comment('允许访问的网址');
            $table->set('status')->bool()->defaultVal(0);
            $table->set('allow_self')->bool()->defaultVal(0)->comment('是否允许后台用户自己添加');
            $table->timestamps();
        })->append(UserTokenModel::tableName(), function (Table $table) {
            $table->setComment('用户授权平台令牌');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('platform_id')->int()->notNull();
            $table->set('token')->text()->notNull();
            $table->set('is_self')->bool()->defaultVal(0)->comment('是否时用户后台添加的');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        })->append(PlatformOptionModel::tableName(), function (Table $table) {
            $table->setComment('平台一些第三方接口配置');
            $table->set('id')->pk(true);
            $table->set('platform_id')->int()->notNull();
            $table->set('store')->varchar(20)->notNull()->comment('平台别名');
            $table->set('name')->varchar(30)->notNull()->comment('字段');
            $table->set('value')->text()->comment('配置值');
            $table->timestamps();
        })->autoUp();
    }
}