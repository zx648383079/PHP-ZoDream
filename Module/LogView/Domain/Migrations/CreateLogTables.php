<?php
namespace Module\LogView\Domain\Migrations;


use Module\LogView\Domain\Model\FileModel;
use Module\LogView\Domain\Model\LogModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateLogTables extends Migration {

    public function up() {
        $this->append(FileModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('name')->varchar();
            $table->column('md5')->varchar(32);
            $table->timestamps();
        })->append(LogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('file_id');
            $table->column('date')->date()->default('0001-01-01');
            $table->column('time')->time()->default('00:00:00');
            $table->column('s_sitename')->varchar(30)->default('');
            $table->column('s_computername')->varchar(30)->default('');
            $table->column('s_ip')->varchar(120)->default('');
            $table->column('cs_method')->varchar(10)->default('');
            $table->column('cs_uri_stem')->varchar()->default('');
            $table->column('cs_uri_query')->varchar()->default('');
            $table->column('s_port')->int(5)->default(80);
            $table->column('cs_username')->varchar(40)->default('');
            $table->column('c_ip')->varchar(120)->default('');
            $table->column('cs_user_agent')->varchar()->default('');
            $table->column('cs_version')->varchar(20)->default('');
            $table->column('cs_referer')->varchar()->default('');
            $table->column('cs_cookie')->varchar()->default('');
            $table->column('cs_host')->varchar()->default('');
            $table->column('sc_status')->int(4)->default(200);
            $table->column('sc_substatus')->int(4)->default(0);
            $table->column('sc_win32_status')->int(4)->default(0);
            $table->column('sc_bytes')->int()->default(0);
            $table->column('cs_bytes')->int()->default(0);
            $table->column('time_taken')->int()->default(0);
        })->autoUp();
    }
}