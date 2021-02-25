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
use Zodream\Http\Uri;

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
        $preview = request()->get('preview');
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
        $url = new Uri(request()->url());
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
                if (str_starts_with($path, ltrim($item->match_rule, '/'))) {
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
            $table->id();
            $table->column('name')->varchar(100)->notNull();
            $table->column('title')->varchar(100)->notNull();
            $table->column('type')->tinyint(1)->defaultVal(0);
            $table->column('model_id')->int()->defaultVal(0);
            $table->column('parent_id')->int()->defaultVal(0);
            $table->column('keywords')->varchar()->defaultVal('');
            $table->column('description')->varchar()->defaultVal('');
            $table->column('thumb')->varchar(100)->defaultVal('')->comment('缩略图');
            $table->column('image')->varchar(100)->defaultVal('')->comment('主图');
            $table->column('content')->text();
            $table->column('url')->varchar(100)->defaultVal('');
            $table->column('position')->tinyint(3)->defaultVal(99);
            $table->column('groups')->varchar()->defaultVal('');
            $table->column('category_template')->varchar(20)->defaultVal('');
            $table->column('list_template')->varchar(20)->defaultVal('');
            $table->column('show_template')->varchar(20)->defaultVal('');
            $table->column('setting')->text();
            $table->timestamps();
        });
        Schema::createTable(ContentModel::tableName(), function (Table $table) {
            $table->id();
            $table->column('title')->varchar(100)->notNull();
            $table->column('cat_id')->int()->notNull();
            $table->column('model_id')->int()->notNull();
            $table->column('parent_id')->int()->defaultVal(0);
            $table->uint('user_id')->default(0);
            $table->column('keywords')->varchar();
            $table->column('thumb')->varchar();
            $table->column('description')->varchar();
            $table->column('status')->bool()->defaultVal(0);
            $table->column('view_count')->int()->defaultVal(0);
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