<?php
namespace Module\SEO\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\SEO\Domain\Model\OptionModel;


class CreateSEOTables extends Migration {

    public function up() {
        $this->append(OptionModel::tableName(), function(Table $table) {
            $table->setComment('全局设置');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(20)->notNull();
            $table->set('code')->varchar(20)->defaultVal('');
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('type')->varchar(20)->defaultVal('text');
            $table->set('visibility')->bool()->defaultVal(1)->comment('是否对外显示');
            $table->set('default_value')->varchar(255)->defaultVal('')->comment('默认值或候选值');
            $table->set('value')->text();
            $table->set('position')->tinyint(4)->defaultVal(99);
        });
        parent::up();
    }

}