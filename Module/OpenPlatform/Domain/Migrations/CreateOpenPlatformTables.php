<?php
namespace Module\OpenPlatform\Domain\Migrations;

use Module\OpenPlatform\Domain\Model\PlatformModel;
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
           $table->set('sign_type')->tinyint(1)->defaultVal(0)->comment('加密方式');
           $table->set('private_key')->text()->defaultVal('');
           $table->set('public_key')->text()->defaultVal('');
           $table->set('status')->bool()->defaultVal(0);
           $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(PlatformModel::tableName());
    }
}