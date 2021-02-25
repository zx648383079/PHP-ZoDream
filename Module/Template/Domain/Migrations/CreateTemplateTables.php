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
            $table->comment('所有可用主题');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('description')->varchar(200)->default('');
            $table->column('thumb')->varchar(100)->default('');
            $table->column('path')->varchar(200);
        })->append(ThemePageModel::tableName(), function(Table $table) {
            $table->comment('所有主题下面页面框架');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('description')->varchar(200)->default('');
            $table->column('thumb')->varchar(100)->default('');
            $table->uint('theme_id');
            $table->column('path')->varchar(200);
        })->append(ThemeStyleModel::tableName(), function(Table $table) {
            $table->comment('所有主题下面部件集成样式');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('description')->varchar(200)->default('');
            $table->column('thumb')->varchar(100)->default('');
            $table->uint('theme_id');
            $table->column('path')->varchar(200);
        })->append(ThemeWeightModel::tableName(), function(Table $table) {
            $table->comment('所有主题组件');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('description')->varchar(200)->default('');
            $table->column('thumb')->varchar(100)->default('');
            $table->uint('type', 2)->default(0);
            $table->uint('adapt_to', 1)->default(0)->comment('适用pc、手机');
            $table->column('editable')->bool()->default(1);
            $table->uint('theme_id');
            $table->column('path')->varchar(200);
        })->append(SiteModel::tableName(), function(Table $table) {
            $table->comment('自定义站点');
            $table->id();
            $table->column('name')->varchar(100);
            $table->uint('user_id');
            $table->column('title')->varchar(200)->default('New Page');
            $table->column('keywords')->varchar(255)->default('');
            $table->column('thumb')->varchar(255)->default('');
            $table->column('description')->varchar(255)->default('');
            $table->column('domain')->varchar(50)->default('');
            $table->uint('theme_id');
            $table->timestamps();
        })->append(PageModel::tableName(), function(Table $table) {
            $table->comment('自定义站点页面');
            $table->id();
            $table->uint('site_id');
            $table->uint('type', 2)->default(0);
            $table->column('name')->varchar(100);
            $table->column('title')->varchar(200)->default('New Page');
            $table->column('keywords')->varchar(255)->default('');
            $table->column('thumb')->varchar(255)->default('');
            $table->column('description')->varchar(255)->default('');
            $table->uint('theme_page_id');
            $table->column('settings')->text()->nullable();
            $table->uint('position', 2)->default(10);
            $table->softDeletes();
            $table->timestamps();
        })->append(PageWeightModel::tableName(), function(Table $table) {
            $table->comment('自定义页面组件及设置');
            $table->column('id')->pk()->ai(1000);  // 预留id给页面预留不同入口
            $table->uint('page_id');
            $table->uint('site_id');
            $table->uint('theme_weight_id');
            $table->uint('parent_id');
            $table->uint('position', 5);
            $table->column('title')->varchar(200);
            $table->column('content')->text()->nullable();
            $table->column('settings')->text()->nullable();
            $table->uint('theme_style_id')->default(0);
            $table->column('is_share')->bool()->default(0);
            $table->timestamps();
        });
        parent::up();
    }

}