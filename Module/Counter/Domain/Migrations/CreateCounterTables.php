<?php
namespace Module\Counter\Domain\Migrations;


use Module\Counter\Domain\Model\ClickLogModel;
use Module\Counter\Domain\Model\JumpLogModel;
use Module\Counter\Domain\Model\LoadTimeLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\PageLogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Module\Counter\Domain\Model\VisitorLogModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCounterTables extends Migration {

    public function up() {
        $this->append(PageLogModel::tableName(), function(Table $table) {
            $table->comment('页面访问记录');
            $table->id();
            $table->column('url')->varchar(255);
            $table->uint('visit_count')->default(0);
        })->append(VisitorLogModel::tableName(), function(Table $table) {
            $table->comment('访客日志');
            $table->id();
            $table->uint('user_id')->varchar(50)->default('');
            $table->column('ip')->varchar(120);
            $table->timestamp('first_at');
            $table->timestamp('last_at');
        })->append(JumpLogModel::tableName(), function(Table $table) {
            $table->comment('页面跳出记录');
            $table->id();
            $table->column('referrer')->varchar()->default('');
            $table->column('url')->varchar();
            $table->column('ip')->varchar(120);
            $table->column('session_id')->varchar(32)->default('');
            $table->column('user_agent')->varchar(255)->default('')->comment('代理');
            $table->timestamp('created_at');
        })->append(ClickLogModel::tableName(), function(Table $table) {
            $table->comment('页面点击记录');
            $table->id();
            $table->column('url')->varchar();
            $table->column('ip')->varchar(120);
            $table->column('session_id')->varchar(32)->default('');
            $table->column('user_agent')->varchar(255)->default('')->comment('代理');
            $table->column('x')->varchar(100)->default(0);
            $table->column('y')->varchar(100)->default(0);
            $table->column('tag')->varchar(120);
            $table->column('tag_url')->varchar(120)->default('');
            $table->timestamp('created_at');
        })->append(LoadTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面加载记录');
            $table->id();
            $table->column('url')->varchar();
            $table->column('ip')->varchar(120);
            $table->column('session_id')->varchar(32)->default('');
            $table->column('user_agent')->varchar(255)->default('')->comment('代理');
            $table->short('load_time', 5)->unsigned();
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->comment('访问记录');
            $table->id();
            $table->column('ip')->varchar(120);
            $table->column('browser')->varchar(40)->default('')->comment('浏览器');
            $table->column('browser_version')->varchar(20)->default('')->comment('浏览器版本');
            $table->column('os')->varchar(20)->default('')->comment('操作系统');
            $table->column('os_version')->varchar(20)->default('')->comment('操作系统版本');
            $table->column('url')->varchar(255)->default('')->comment('请求网址');
            $table->column('referrer')->varchar(255)->default('')->comment('来路');
            $table->column('user_agent')->varchar(255)->default('')->comment('代理');
            $table->column('country')->varchar(45)->default('');
            $table->column('region')->varchar(45)->default('');
            $table->column('city')->varchar(45)->default('');
            $table->uint('user_id')->default(0);
            $table->column('session_id')->varchar(32)->default('');
            $table->column('language')->varchar(20)->default('');
            $table->column('latitude')->varchar(30)->default('')->comment('纬度');
            $table->column('longitude')->varchar(30)->default('')->comment('经度');
            $table->timestamp('created_at');
        })->append(StayTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面停留时间');
            $table->id();
            $table->column('url')->varchar();
            $table->column('ip')->varchar(120);
            $table->column('user_agent')->varchar(255)->default('')->comment('代理');
            $table->column('session_id')->varchar(32)->default('');
            $table->column('status')->bool()->default(0)
                ->comment('是否停留');
            $table->timestamp('enter_at');
            $table->timestamp('leave_at');
        })->autoUp();
    }
}
