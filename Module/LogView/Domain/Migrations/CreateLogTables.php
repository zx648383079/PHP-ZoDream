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
            $table->string('name');
            $table->char('md5', 32);
            $table->timestamps();
        })->append(LogModel::tableName(), function (Table $table) {
            $table->id();
            $table->uint('file_id');
            $table->date('date')->default('0001-01-01');
            $table->time('time')->default('00:00:00');
            $table->string('s_sitename', 30)->default('');
            $table->string('s_computername', 30)->default('');
            $table->string('s_ip', 120)->default('');
            $table->string('cs_method', 10)->default('');
            $table->string('cs_uri_stem')->default('');
            $table->string('cs_uri_query')->default('');
            $table->uint('s_port', 5)->default(80);
            $table->string('cs_username', 40)->default('');
            $table->string('c_ip', 120)->default('');
            $table->string('cs_user_agent')->default('');
            $table->string('cs_version', 20)->default('');
            $table->string('cs_referer')->default('');
            $table->string('cs_cookie')->default('');
            $table->string('cs_host')->default('');
            $table->uint('sc_status', 4)->default(200);
            $table->uint('sc_substatus', 4)->default(0);
            $table->uint('sc_win32_status', 4)->default(0);
            $table->uint('sc_bytes')->default(0);
            $table->uint('cs_bytes')->default(0);
            $table->uint('time_taken')->default(0);
        })->autoUp();
    }
}