<?php
namespace Module\Template\Domain\Migrations;

use Module\Template\Domain\Model\OptionModel;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\WeightModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateTemplateTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::createTable(SiteModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(200)->defaultVal('New Page');
            $table->set('keywords')->varchar(255);
            $table->set('thumb')->varchar(255);
            $table->set('description')->varchar(255);
            $table->set('domain')->varchar(50);
            $table->timestamps();
        });
        Schema::createTable(PageModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('site_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(200)->defaultVal('New Page');
            $table->set('keywords')->varchar(255);
            $table->set('thumb')->varchar(255);
            $table->set('description')->varchar(255);
            $table->set('template')->varchar(255);
            $table->set('settings')->text();
            $table->set('position')->tinyint(2)->defaultVal(10);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(PageWeightModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai(1000);  // 预留id给页面预留不同入口
            $table->set('page_id')->int()->notNull();
            $table->set('weight_id')->int()->notNull();
            $table->set('parent_id')->int(10);
            $table->set('position')->int(5);
            $table->set('title')->varchar(200);
            $table->set('content')->text();
            $table->set('settings')->text();
            $table->set('is_share')->bool()->defaultVal(0);
            $table->timestamps();
        });
        Schema::createTable(WeightModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('description')->varchar(200);
            $table->set('thumb')->varchar(100);
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('adapt_to')->tinyint(1)->defaultVal(0)->comment('适用pc、手机');
            $table->set('editable')->bool()->defaultVal(1);
            $table->set('path')->varchar(200);
        });
        Schema::createTable(OptionModel::tableName(), function(Table $table) {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(PageModel::tableName());
        Schema::dropTable(PageWeightModel::tableName());
        Schema::dropTable(WeightModel::tableName());
        Schema::dropTable(OptionModel::tableName());
    }
}