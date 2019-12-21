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
            $table->setComment('页面访问记录');
            $table->set('id')->pk(true);
            $table->set('url')->varchar(255)->notNull();
            $table->set('visit_count')->int()->defaultVal(0);
        })->append(VisitorLogModel::tableName(), function(Table $table) {
            $table->setComment('访客日志');
            $table->set('id')->pk(true);
            $table->set('user_id')->varchar(50)->defaultVal('');
            $table->set('ip')->varchar(120)->notNull();
            $table->timestamp('first_at');
            $table->timestamp('last_at');
        })->append(JumpLogModel::tableName(), function(Table $table) {
            $table->setComment('页面跳出记录');
            $table->set('id')->pk(true);
            $table->set('referrer')->varchar()->notNull();
            $table->set('url')->varchar()->notNull();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('session_id')->varchar(32)->defaultVal('');
            $table->set('user_agent')->varchar(255)->defaultVal('')->comment('代理');
            $table->timestamp('created_at');
        })->append(ClickLogModel::tableName(), function(Table $table) {
            $table->setComment('页面点击记录');
            $table->set('id')->pk(true);
            $table->set('url')->varchar()->notNull();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('session_id')->varchar(32)->defaultVal('');
            $table->set('user_agent')->varchar(255)->defaultVal('')->comment('代理');
            $table->set('x')->varchar(100)->defaultVal(0);
            $table->set('y')->varchar(100)->defaultVal(0);
            $table->set('tag')->varchar(120)->notNull();
            $table->set('tag_url')->varchar(120)->defaultVal('');
            $table->timestamp('created_at');
        })->append(LoadTimeLogModel::tableName(), function(Table $table) {
            $table->setComment('页面加载记录');
            $table->set('id')->pk(true);
            $table->set('url')->varchar()->notNull();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('session_id')->varchar(32)->defaultVal('');
            $table->set('user_agent')->varchar(255)->defaultVal('')->comment('代理');
            $table->set('load_time')->smallInt(5)->notNull();
            $table->timestamp('created_at');
        })->append(LogModel::tableName(), function(Table $table) {
            $table->setComment('访问记录');
            $table->set('id')->pk(true);
            $table->set('ip')->varchar(120)->notNull();
            $table->set('browser')->varchar(40)->defaultVal('')->comment('浏览器');
            $table->set('browser_version')->varchar(20)->defaultVal('')->comment('浏览器版本');
            $table->set('os')->varchar(20)->defaultVal('')->comment('操作系统');
            $table->set('os_version')->varchar(20)->defaultVal('')->comment('操作系统版本');
            $table->set('url')->varchar(255)->defaultVal('')->comment('请求网址');
            $table->set('referrer')->varchar(255)->defaultVal('')->comment('来路');
            $table->set('user_agent')->varchar(255)->defaultVal('')->comment('代理');
            $table->set('country')->varchar(45)->defaultVal('');
            $table->set('region')->varchar(45)->defaultVal('');
            $table->set('city')->varchar(45)->defaultVal('');
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('session_id')->varchar(32)->defaultVal('');
            $table->set('language')->varchar(20)->defaultVal('');
            $table->set('latitude')->varchar(30)->defaultVal('')->comment('纬度');
            $table->set('longitude')->varchar(30)->defaultVal('')->comment('经度');
            $table->timestamp('created_at');
        })->append(StayTimeLogModel::tableName(), function(Table $table) {
            $table->setComment('页面停留时间');
            $table->set('id')->pk(true);
            $table->set('url')->varchar()->notNull();
            $table->set('ip')->varchar(120)->notNull();
            $table->set('user_agent')->varchar(255)->defaultVal('')->comment('代理');
            $table->set('session_id')->varchar(32)->defaultVal('');
            $table->set('status')->bool()->defaultVal(0)
                ->comment('是否停留');
            $table->timestamp('enter_at');
            $table->timestamp('leave_at');
        });
        parent::up();
    }
}
