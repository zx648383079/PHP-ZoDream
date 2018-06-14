<?php
namespace Module\LogView\Domain\Migrations;


use Module\LogView\Domain\Model\FileModel;
use Module\LogView\Domain\Model\LogModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateLogTables extends Migration {

    public function up() {
        Schema::createTable(FileModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar()->notNull();
            $table->set('md5')->varchar(32)->notNull();
            $table->timestamps();
        });
        Schema::createTable(LogModel::tableName(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('file_id')->int()->notNull();
            $table->set('date')->date()->defaultVal('0000-00-00');
            $table->set('time')->time()->defaultVal('00:00:00');
            $table->set('s_sitename')->varchar(30)->defaultVal('');
            $table->set('s_computername')->varchar(30)->defaultVal('');
            $table->set('s_ip')->varchar(120)->defaultVal('');
            $table->set('cs_method')->varchar(10)->defaultVal('');
            $table->set('cs_uri_stem')->varchar()->defaultVal('');
            $table->set('cs_uri_query')->varchar()->defaultVal('');
            $table->set('s_port')->int(5)->defaultVal(80);
            $table->set('cs_username')->varchar(40)->defaultVal('');
            $table->set('c_ip')->varchar(120)->defaultVal('');
            $table->set('cs_user_agent')->varchar()->defaultVal('');
            $table->set('cs_version')->varchar(20)->defaultVal('');
            $table->set('cs_referer')->varchar()->defaultVal('');
            $table->set('cs_cookie')->varchar()->defaultVal('');
            $table->set('cs_host')->varchar()->defaultVal('');
            $table->set('sc_status')->int(4)->defaultVal(200);
            $table->set('sc_substatus')->int(4)->defaultVal(0);
            $table->set('sc_win32_status')->int(4)->defaultVal(0);
            $table->set('sc_bytes')->int()->defaultVal(0);
            $table->set('cs_bytes')->int()->defaultVal(0);
            $table->set('time_taken')->int()->defaultVal(0);
        });
    }

    public function down() {
        Schema::dropTable(FileModel::tableName());
        Schema::dropTable(LogModel::tableName());
    }
}