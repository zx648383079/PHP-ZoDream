<?php
namespace Module\Bug\Domain\Migrations;

use Module\Bug\Domain\Model\BugModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateBugTables extends Migration {

    public function up() {
        Schema::createTable(BugModel::tableName(), function (Table $table) {
            $table->setComment('BUG 列表');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar()->notNull()->comment('标题');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('类型');
            $table->set('uri')->varchar()->defaultVal('')->comment('漏洞网址或路径');
            $table->set('grade')->tinyint(2)->defaultVal(5)->comment('危害等级');
            $table->set('related')->varchar()->defaultVal('')->comment('影响产品');
            $table->set('description')->varchar()->defaultVal('')->comment('说明');
            $table->set('check_rule')->varchar()->defaultVal('')->comment('验证是否受影响检测规则');
            $table->set('solution')->varchar()->defaultVal('')->comment('解决方案');
            $table->set('source')->varchar()->defaultVal('')->comment('漏洞来源');
            $table->set('status')->bool()->defaultVal(0);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropTable(BugModel::tableName());
    }
}