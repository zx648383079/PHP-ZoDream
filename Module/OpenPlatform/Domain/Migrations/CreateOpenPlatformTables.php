<?php
namespace Module\OpenPlatform\Domain\Migrations;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateOpenPlatformTables extends Migration {

    public function up() {
        Schema::createTable(PlatformModel::tableName(), function (Table $table) {
           $table->setComment('第三方授权信息');
           $table->set('id')->pk()->ai();
           $table->set('user_id')->int()->notNull();
           $table->set('name')->varchar(20)->notNull();
           $table->set('type')->tinyint(1)->notNull()->defaultVal(0);
           $table->set('domain')->varchar(50)->notNull();
           $table->set('appid')->char(12)->unique()->notNull();
           $table->set('secret')->char(32)->notNull();
           $table->set('sign_type')->tinyint(1)->defaultVal(0)->comment('签名方式');
           $table->set('sign_key')->varchar(32)->comment('签名密钥');
           $table->set('encrypt_type')->tinyint(1)->defaultVal(0)->comment('加密方式');
           $table->set('public_key')->text()->comment('密钥');
           $table->set('status')->bool()->defaultVal(0);
           $table->timestamps();
        });
        Schema::createTable(UserTokenModel::tableName(), function (Table $table) {
            $table->setComment('用户授权平台令牌');
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('platform_id')->int()->notNull();
            $table->set('token')->char(32)->notNull();
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(PlatformModel::tableName());
        Schema::dropTable(UserTokenModel::tableName());
    }
}