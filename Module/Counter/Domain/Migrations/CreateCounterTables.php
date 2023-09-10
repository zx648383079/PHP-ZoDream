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

    public function up(): void {
        $this->append(PageLogModel::tableName(), function(Table $table) {
            $table->comment('页面访问记录');
            $table->id();
            $table->string('url');
            $table->uint('visit_count')->default(0);
        })->append(VisitorLogModel::tableName(), function(Table $table) {
            $table->comment('访客日志');
            $table->id();
            $table->uint('user_id')->varchar(50)->default('');
            $table->string('ip', 120);
            $table->timestamp('first_at');
            $table->timestamp('last_at');
        })->append(JumpLogModel::tableName(), function(Table $table) {
            $table->comment('页面跳出记录');
            $table->id();
            $table->string('referrer')->default('');
            $table->string('url');
            $table->string('ip', 120);
            $table->string('session_id', 32)->default('');
            $table->string('user_agent')->default('')->comment('代理');
            $table->timestamp('created_at');
        })->append(ClickLogModel::tableName(), function(Table $table) {
            $table->comment('页面点击记录');
            $table->id();
            $table->string('url');
            $table->string('ip', 120);
            $table->string('session_id', 32)->default('');
            $table->string('user_agent')->default('')->comment('代理');
            $table->string('x', 100)->default(0);
            $table->string('y', 100)->default(0);
            $table->string('tag', 120);
            $table->string('tag_url', 120)->default('');
            $table->timestamp('created_at');
        })->append(LoadTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面加载记录');
            $table->id();
            $table->string('url');
            $table->string('ip', 120);
            $table->string('session_id', 32)->default('');
            $table->string('user_agent')->default('')->comment('代理');
            $table->uint('load_time', 5);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->comment('访问记录');
            $table->id();
            $table->string('ip', 120);
            $table->string('browser', 40)->default('')->comment('浏览器');
            $table->string('browser_version', 20)->default('')->comment('浏览器版本');
            $table->string('os', 20)->default('')->comment('操作系统');
            $table->string('os_version', 20)->default('')->comment('操作系统版本');
            $table->string('url')->default('')->comment('请求网址');
            $table->string('referrer')->default('')->comment('来路');
            $table->string('user_agent')->default('')->comment('代理');
            $table->string('country', 45)->default('');
            $table->string('region', 45)->default('');
            $table->string('city', 45)->default('');
            $table->uint('user_id')->default(0);
            $table->string('session_id', 32)->default('');
            $table->string('language', 20)->default('');
            $table->string('latitude', 30)->default('')->comment('纬度');
            $table->string('longitude', 30)->default('')->comment('经度');
            $table->timestamp('created_at');
        })->append(StayTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面停留时间');
            $table->id();
            $table->string('url');
            $table->string('ip', 120);
            $table->string('user_agent')->default('')->comment('代理');
            $table->string('session_id', 32)->default('');
            $table->bool('status')->default(0)
                ->comment('是否停留');
            $table->timestamp('enter_at');
            $table->timestamp('leave_at');
        })->autoUp();
    }
}
