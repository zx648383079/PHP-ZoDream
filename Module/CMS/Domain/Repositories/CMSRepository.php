<?php
namespace Module\CMS\Domain\Repositories;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\BaseScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\ThemeManager;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CMSRepository {

    /**
     * @var SiteModel
     */
    private static $cacheSite;

    /**
     * @var string
     */
    private static $cacheTheme;

    public static function theme() {
        if (!empty(self::$cacheTheme)) {
            return self::$cacheTheme;
        }
        $preview = app('request')->get('preview');
        if (!empty($preview)) {
            return self::$cacheTheme = $preview;
        }
        if (empty(static::site()->theme)) {
            return self::$cacheTheme = 'default';
        }
        return self::$cacheTheme = static::site()->theme;
    }

    /**
     * @return BaseScene
     * @throws \Exception
     */
    public static function scene() {
        return app(SceneInterface::class);
    }

    /**
     * @return SiteModel
     * @throws \Exception
     */
    public static function site() {
        if (!empty(self::$cacheSite)){
            return self::$cacheSite;
        }
        /** @var SiteModel[] $model_list */
        $model_list = SiteModel::query()->all();
        if (empty($model_list)) {
            throw new \Exception('无任何站点');
        }
        $default = $model_list[0];
        $url = app('request')->uri();
        $path = ltrim($url->getPath(), '/');
        foreach ($model_list as $item) {
            if ($item->is_default) {
                $default = $item;
            }
            if ($item->match_type == SiteModel::MATCH_TYPE_DOMAIN) {
                if ($item->match_rule === $url->getHost()) {
                    return self::$cacheSite = $item;
                }
                continue;
            }
            if ($item->match_type == SiteModel::MATCH_TYPE_PATH) {
                if (strpos($path, ltrim($item->match_rule, '/')) === 0) {
                    return self::$cacheSite = $item;
                }
                continue;
            }
        }
        return self::$cacheSite = $default;
    }

    public static function siteId() {
        return static::site()->id;
    }

    public static function generateSite(SiteModel $site) {
        self::$cacheSite = $site;
        Schema::createTable(CategoryModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('title')->varchar(100)->notNull();
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('model_id')->int()->defaultVal(0);
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar()->defaultVal('');
            $table->set('description')->varchar()->defaultVal('');
            $table->set('thumb')->varchar(100)->defaultVal('')->comment('缩略图');
            $table->set('image')->varchar(100)->defaultVal('')->comment('主图');
            $table->set('content')->text();
            $table->set('url')->varchar(100)->defaultVal('');
            $table->set('position')->tinyint(3)->defaultVal(99);
            $table->set('groups')->varchar()->defaultVal('');
            $table->set('category_template')->varchar(20)->defaultVal('');
            $table->set('list_template')->varchar(20)->defaultVal('');
            $table->set('show_template')->varchar(20)->defaultVal('');
            $table->set('setting')->text();
            $table->timestamps();
        });
        Schema::createTable(ContentModel::tableName(), function (Table $table) {
            $table->set('id')->pk(true);
            $table->set('title')->varchar(100)->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('model_id')->int()->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar();
            $table->set('thumb')->varchar();
            $table->set('description')->varchar();
            $table->set('status')->bool()->defaultVal(0);
            $table->set('view_count')->int()->defaultVal(0);
            $table->timestamps();
        });
        (new ThemeManager())->apply($site->theme);
    }

    public static function generateCategoryTable(CategoryModel $model) {
        if ($model->type > 0 || !$model->model) {
            return;
        }
        CMSRepository::scene()->setModel($model->model)->initTable();
    }

    public static function removeSite(SiteModel $site) {
        $model_list = ModelModel::query()->get();
        $old = self::$cacheSite;
        self::$cacheSite = $site;
        Schema::dropTable(CategoryModel::tableName());
        Schema::dropTable(ContentModel::tableName());
        foreach ($model_list as $item) {
            CMSRepository::scene()->setModel($item)->removeTable();
        }
        self::$cacheSite = $old;
    }

    public static function removeModel(ModelModel $model) {
        $site_list = SiteModel::query()->pluck('id');
        foreach ($site_list as $item) {
            CMSRepository::scene()->setModel($model, $item)->removeTable();
        }
    }

    public static function resetSite($id = 0) {
        if ($id > 0) {
            session([
                'cms_site' => $id
            ]);
        }
        $id = session('cms_site');
        if ($id > 0) {
            static::$cacheSite = SiteModel::find($id);
            return;
        }
    }
}