<?php
namespace Module\Auth\Domain\Migrations;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Auth\Domain\Model\UserModel;
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
            $table->set('password')->varchar(100);
            $table->set('sex')->tinyint(1);
            $table->set('avatar')->varchar(255);
            $table->set('token')->varchar(60);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(OAuthModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('user_id')->int()->notNull();
            $table->set('vendor')->varchar(30);
            $table->set('identity')->varchar(100);
            $table->set('data')->text();
            $table->timestamp('created_at');
        });
        Schema::createTable(LoginLogModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user')->varchar(100);
            $table->set('status')->bool()->defaultVal(0);
            $table->set('mode')->varchar(20);
            $table->timestamp('created_at');
        });
        Schema::createTable(UserMetaModel::tableName(), function(Table $table) {
            $table->set('user_id')->int()->notNull();
            $table->set('key')->varchar(100)->notNull();
            $table->set('value')->text()->defaultVal('');
        });
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
    }
}