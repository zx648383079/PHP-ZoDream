<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Migrations;

use Module\Counter\Domain\Model\AnalysisFlagModel;
use Module\Counter\Domain\Model\ClickLogModel;
use Module\Counter\Domain\Model\HostnameModel;
use Module\Counter\Domain\Model\JumpLogModel;
use Module\Counter\Domain\Model\LoadTimeLogModel;
use Module\Counter\Domain\Model\LogModel;
use Module\Counter\Domain\Model\PageLogModel;
use Module\Counter\Domain\Model\PathnameModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Module\Counter\Domain\Model\VisitorLogModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCounterTables extends Migration {

    public function up(): void {
        $this->append(HostnameModel::tableName(), function(Table $table) {
            $table->comment('域名');
            $table->id();
            $table->string('name');
        })->append(PathnameModel::tableName(), function(Table $table) {
            $table->comment('页面');
            $table->id();
            $table->string('name');
        })->append(AnalysisFlagModel::tableName(), function(Table $table) {
            $table->comment('分析数据用的标记');
            $table->id();
            $table->uint('item_type', 1);
            $table->string('item_value');
            $table->uint('flags');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->append(PageLogModel::tableName(), function(Table $table) {
            $table->comment('页面访问记录');
            $table->id();
            $table->uint('host_id');
            $table->uint('path_id');
            $table->uint('visit_count')->default(0);
        })->append(VisitorLogModel::tableName(), function(Table $table) {
            $table->comment('访客日志');
            $table->id();
            $table->uint('user_id')->default(0);
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
            $table->uint('log_id')->comment('关联记录');
            $table->string('x', 100)->default(0);
            $table->string('y', 100)->default(0);
            $table->string('tag', 120)->comment('点击页面标签');
            $table->string('tag_url')->default('')->comment('点击页面标签链接');
            $table->timestamp('created_at');
        })->append(LoadTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面加载记录');
            $table->id();
            $table->uint('log_id')->comment('关联记录');
            $table->uint('load_time', 5);
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->comment('访问记录');
            $table->id();
            $table->string('ip', 120);
            $table->string('hostname')->default('')->comment('域名');
            $table->string('pathname')->default('')->comment('访问路径');
            $table->string('queries', 1000)->default('')->comment('查询参数');
            $table->string('referrer_hostname')->default('')->comment('来路域名');
            $table->string('referrer_pathname')->default('')->comment('来路路径及参数');
            $table->string('user_agent', 1000)->default('')->comment('代理');
            $table->string('method', 10)->default('GET')->comment('请求方法');
            $table->uint('status_code')->default('200')->comment('响应的状态码');
            $table->uint('user_id')->default(0);
            $table->string('session_id', 32)->default('');
            $table->string('language', 20)->default('');
            $table->string('latitude', 30)->default('')->comment('纬度');
            $table->string('longitude', 30)->default('')->comment('经度');
            $table->timestamp('created_at');
        })->append(StayTimeLogModel::tableName(), function(Table $table) {
            $table->comment('页面停留时间');
            $table->id();
            $table->uint('log_id')->comment('关联记录');
            $table->bool('status')->default(0)
                ->comment('是否停留');
            $table->timestamp('enter_at');
            $table->timestamp('leave_at');
        })->autoUp();
    }
}
