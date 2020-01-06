<?php
namespace Module\Template\Domain\Migrations;

use Module\Template\Domain\Model\Base\OptionModel;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTemplateTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(ThemeModel::tableName(), function(Table $table) {
            $table->setComment('所有可用主题');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('description')->varchar(200)->defaultVal('');
            $table->set('thumb')->varchar(100)->defaultVal('');
            $table->set('path')->varchar(200)->notNull();
        })->append(ThemePageModel::tableName(), function(Table $table) {
            $table->setComment('所有主题下面页面框架');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('description')->varchar(200)->defaultVal('');
            $table->set('thumb')->varchar(100)->defaultVal('');
            $table->set('theme_id')->int()->notNull();
            $table->set('path')->varchar(200)->notNull();
        })->append(ThemeWeightModel::tableName(), function(Table $table) {
            $table->setComment('所有主题组件');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(30)->notNull();
            $table->set('description')->varchar(200);
            $table->set('thumb')->varchar(100);
            $table->set('type')->tinyint(3)->defaultVal(0);
            $table->set('adapt_to')->tinyint(1)->defaultVal(0)->comment('适用pc、手机');
            $table->set('editable')->bool()->defaultVal(1);
            $table->set('theme_id')->int()->notNull();
            $table->set('path')->varchar(200);
        })->append(SiteModel::tableName(), function(Table $table) {
            $table->setComment('自定义站点');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(100)->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('title')->varchar(200)->defaultVal('New Page');
            $table->set('keywords')->varchar(255)->defaultVal('');
            $table->set('thumb')->varchar(255)->defaultVal('');
            $table->set('description')->varchar(255)->defaultVal('');
            $table->set('domain')->varchar(50)->defaultVal('');
            $table->set('theme_id')->int()->notNull();
            $table->timestamps();
        })->append(PageModel::tableName(), function(Table $table) {
            $table->setComment('自定义站点页面');
            $table->set('id')->pk()->ai();
            $table->set('site_id')->int()->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(200)->defaultVal('New Page');
            $table->set('keywords')->varchar(255)->defaultVal('');
            $table->set('thumb')->varchar(255)->defaultVal('');
            $table->set('description')->varchar(255)->defaultVal('');
            $table->set('theme_page_id')->int()->notNull();
            $table->set('settings')->text();
            $table->set('position')->tinyint(2)->defaultVal(10);
            $table->softDeletes();
            $table->timestamps();
        })->append(PageWeightModel::tableName(), function(Table $table) {
            $table->setComment('自定义页面组件及设置');
            $table->set('id')->pk()->ai(1000);  // 预留id给页面预留不同入口
            $table->set('page_id')->int()->notNull();
            $table->set('site_id')->int()->notNull();
            $table->set('theme_weight_id')->int()->notNull();
            $table->set('parent_id')->int(10);
            $table->set('position')->int(5);
            $table->set('title')->varchar(200);
            $table->set('content')->text();
            $table->set('settings')->text();
            $table->set('is_share')->bool()->defaultVal(0);
            $table->timestamps();
        });
        $this->createBaseTables();
        parent::up();
    }

    private function createBaseTables(): void {
        $this->append(OptionModel::tableName(), function (Table $table) {
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
}