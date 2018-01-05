<?php
namespace Module\Template\Domain\Migrations;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
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
        Schema::createTable(PageModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(200)->notNull()->defaultVal('New Page');
            $table->set('keywords')->varchar(255);
            $table->set('description')->varchar(255);
            $table->set('template')->varchar(255);
            $table->set('settings')->text();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(PageWeightModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai(1000);  // 预留id给页面预留不同入口
            $table->set('name')->varchar(100)->notNull();
            $table->set('weight_name')->varchar(30);
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
            $table->set('type')->tinyint(3);
            $table->set('adapt_to')->tinyint(1)->defaultVal(0)->comment('适用pc、手机');
            $table->set('editable')->bool()->defaultVal(1);
            $table->set('path')->varchar(200);
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
    }
}