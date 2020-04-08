<?php
namespace Module\Template\Domain\Migrations;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Model\ThemeStyleModel;
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
        })->append(ThemeStyleModel::tableName(), function(Table $table) {
            $table->setComment('所有主题下面部件集成样式');
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
            $table->set('type')->tinyint(1)->defaultVal(0);
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
            $table->set('theme_style_id')->int()->defaultVal(0);
            $table->set('is_share')->bool()->defaultVal(0);
            $table->timestamps();
        });
        parent::up();
    }

}