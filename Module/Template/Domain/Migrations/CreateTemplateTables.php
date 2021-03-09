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
            $table->string('name', 30);
            $table->string('description')->default('');
            $table->string('thumb', 100)->default('');
            $table->string('path');
        })->append(ThemePageModel::tableName(), function(Table $table) {
            $table->comment('所有主题下面页面框架');
            $table->id();
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->uint('theme_id');
            $table->string('path', 200);
        })->append(ThemeStyleModel::tableName(), function(Table $table) {
            $table->comment('所有主题下面部件集成样式');
            $table->id();
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->uint('theme_id');
            $table->string('path', 200);
        })->append(ThemeWeightModel::tableName(), function(Table $table) {
            $table->comment('所有主题组件');
            $table->id();
            $table->string('name', 30);
            $table->string('description', 200)->default('');
            $table->string('thumb', 100)->default('');
            $table->uint('type', 2)->default(0);
            $table->uint('adapt_to', 1)->default(0)->comment('适用pc、手机');
            $table->bool('editable')->default(1);
            $table->uint('theme_id');
            $table->string('path', 200);
        })->append(SiteModel::tableName(), function(Table $table) {
            $table->comment('自定义站点');
            $table->id();
            $table->string('name', 100);
            $table->uint('user_id');
            $table->string('title', 200)->default('New Page');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('description')->default('');
            $table->string('domain', 50)->default('');
            $table->uint('theme_id');
            $table->timestamps();
        })->append(PageModel::tableName(), function(Table $table) {
            $table->comment('自定义站点页面');
            $table->id();
            $table->uint('site_id');
            $table->uint('type', 2)->default(0);
            $table->string('name', 100);
            $table->string('title', 200)->default('New Page');
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('description')->default('');
            $table->uint('theme_page_id');
            $table->text('settings')->nullable();
            $table->uint('position', 2)->default(10);
            $table->softDeletes();
            $table->timestamps();
        })->append(PageWeightModel::tableName(), function(Table $table) {
            $table->comment('自定义页面组件及设置');
            $table->id()->ai(1000);  // 预留id给页面预留不同入口
            $table->uint('page_id');
            $table->uint('site_id');
            $table->uint('theme_weight_id');
            $table->uint('parent_id');
            $table->uint('position', 5);
            $table->string('title', 200);
            $table->text('content')->nullable();
            $table->text('settings')->nullable();
            $table->uint('theme_style_id')->default(0);
            $table->bool('is_share')->default(0);
            $table->timestamps();
        })->autoUp();
    }

}