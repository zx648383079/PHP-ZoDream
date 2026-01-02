<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception as GlobalException;
use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Entities\CommentEntity;
use Module\CMS\Domain\Entities\ContentEntity;
use Module\CMS\Domain\Entities\SiteLogEntity;
use Module\CMS\Domain\Entities\LinkageEntity;
use Module\CMS\Domain\Entities\LinkageDataEntity;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\BaseScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\ThemeManager;
use Zodream\Database\Model\Model;
use Zodream\Database\Schema\Table;
use Zodream\Disk\Directory;
use Zodream\Helpers\Json;
use Zodream\Helpers\PinYin;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Error\Exception;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Template\ViewFactory;
use Module\CMS\Domain\Middleware\CMSSeoMiddleware;

class CMSRepository {
    const MANAGE_ROLE = 'cms_manage';
    const PREVIEW_KEY = '_preview';

    /**
     * @var SiteModel
     */
    private static mixed $cacheSite;

    /**
     * @var string
     */
    private static string $cacheTheme = '';

    private static Directory|null $viewFolder = null;

    public static function isPreview(): bool {
        return array_key_exists(self::PREVIEW_KEY, $_GET);
    }

    public static function theme() {
        if (!empty(self::$cacheTheme)) {
            return self::$cacheTheme;
        }
        $preview = request()->get(self::PREVIEW_KEY);
        if (!empty($preview) && !is_numeric($preview)) {
            return self::$cacheTheme = $preview;
        }
        if (empty(static::site()->theme)) {
            return self::$cacheTheme = 'default';
        }
        return self::$cacheTheme = static::site()->theme;
    }

    public static function registerLocate(Directory $folder, string|null $language) {
        if (empty($language)) {
            $language = 'zh-cn';
        }
        $file = $folder->file(sprintf('languages/%s.json', $language));
        if (!$file->exist()) {
            return;
        }
        FuncHelper::$translateItems = Json::decode($file->read());
    }

    public static function registerView(string|SiteModel $theme = '',
                                        ViewFactory|null $provider = null): ViewFactory {
        $language = '';
        if (empty($theme)) {
            $theme = static::theme();
            $language = static::site()->language;
        } elseif ($theme instanceof SiteModel) {
            $language = $theme->language;
            $theme = $theme->theme;
        }
        if (empty($provider)) {
            $provider = view();
        }
        if (empty(static::$viewFolder)) {
            static::$viewFolder = $provider->getDirectory();
        }
        $dir = ThemeManager::themeRootFolder()
            ->directory($theme);
        if (!$dir->exist()) {
            throw new Exception('THEME IS ERROR!');
        }
        $provider->setDirectory($dir)
            ->setEngine(FuncHelper::register(new ParserCompiler(), $provider))
            ->setConfigs([
                'suffix' => '.html'
            ]);
        static::registerLocate($dir, $language);
        return $provider;
    }

    public static function viewTemporary(callable $cb) {
        return view()->temporary(function ($provider) use ($cb) {
            static::registerView('', $provider);
            return call_user_func($cb, $provider);
        });
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
    public static function site(mixed $model = null) {
        if (!empty($model)) {
            static::$cacheSite = $model;
        }
        if (!empty(self::$cacheSite)){
            return self::$cacheSite;
        }
        $request = request();
        $site = static::matchSite($request->scheme(), $request->host(), ltrim($request->path(), '/'));
        if ($site < 1) {
            throw new \Exception('无任何站点');
        }
        return self::$cacheSite = SiteModel::findOrThrow($site);
    }

    public static function matchSite(string $scheme, string $host, string $path, bool $isStrict = false): int {
        $items = self::isPreview() ? SiteRepository::getAll() : CacheRepository::getSiteCache();
        if (empty($items)) {
            return 0;
        }
        $default = $isStrict ? 0 : $items[0]['id'];
        foreach ($items as $item) {
            if (!$isStrict && $item['is_default'] > 0) {
                $default = $item['id'];
            }
            $rules = parse_url((string)$item['match_rule']);
            if (!empty($rules['scheme']) && $rules['scheme'] !== $scheme) {
                continue;
            }
            if (!empty($rules['host']) && $rules['host'] !== $host) {
                continue;
            }
            $rule = trim($rules['path'], '/');
            if ($rule === $path) {
                return $item['id'];
            }
            if ($rule === '') {
                $default = $item['id'];
                continue;
            }
            if (str_starts_with($path, $rule.'/')) {
                return $item['id'];
            }
        }
        return $default;
    }

    public static function siteId(): int {
        return intval(static::site()->id);
    }

    public static function siteLanguage(): string {
        return (string)static::site()->language;
    }

    public static function generateSite(SiteModel $site) {
        self::$cacheSite = $site;
        CreateCmsTables::createTable(CategoryEntity::tableName(), function (Table $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('title', 100);
            $table->uint('type', 1)->default(0);
            $table->uint('model_id')->default(0);
            $table->uint('parent_id')->default(0);
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->string('thumb', 100)->default('')->comment('缩略图');
            $table->string('image', 100)->default('')->comment('主图');
            $table->text('content')->nullable();
            $table->string('url', 100)->default('');
            $table->uint('position', 2)->default(99);
            $table->string('groups')->default('');
            $table->string('category_template', 20)->default('');
            $table->string('list_template', 20)->default('');
            $table->string('show_template', 20)->default('');
            $table->text('setting')->nullable();
            $table->timestamps();
        });
        CreateCmsTables::createTable(SiteLogEntity::tableName(), function (Table $table) {
            $table->id();
            $table->uint('model_id');
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('action');
            $table->timestamp(Model::CREATED_AT);
        });
        static::scene()->boot();
        (new ThemeManager())->apply($site->theme);
    }

    public static function generateCategoryTable(CategoryModel $model) {
        if ($model->type > 0 || !$model->model) {
            return;
        }
        static::scene()->setModel($model->model)->initTable();
    }

    public static function removeSite(SiteModel $site) {
        $model_list = ModelModel::query()->get();
        $old = self::$cacheSite;
        self::$cacheSite = $site;
        CreateCmsTables::dropTable(SiteLogEntity::tableName());
        CreateCmsTables::dropTable(CategoryEntity::tableName());
        CreateCmsTables::dropTable(ContentEntity::tableName());
        CreateCmsTables::dropTable(CommentEntity::tableName());
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

    public static function resetSite(int $id = 0) {
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

    public static function generateTableName(string $name): string {
        if (empty($name)) {
            return Str::randomByNumber(8);
        }
        $val = PinYin::encode($name, 'all');
        return empty($val) ? Str::randomByNumber(8) : str_replace(' ', '_', $val);
    }

    public static function queryUrl(array $step, string $keywords = ''): array {
        if (empty($step)) {
            return [
                ['name' => '联动项', 'next' => 'linkage'],
                ['name' => '栏目', 'next' => 'channel'],
            ];
        }
        if ($step[0] === 'channel') {
            $items = CategoryModel::query()
                ->when(!empty($keywords), function ($query) {
                    SearchModel::searchWhere($query, ['title']);
                })
                ->orderBy('position', 'asc')
                ->asArray()
                ->get('id', 'name', 'title');
            return array_map(function($item) {
                return [
                    'name' => $item['title'],
                    'value' => CMSSeoMiddleware::encodeUrl($item['id'], false)
                ];
            }, $items);
        }
        if (empty($step[1])) {
            $items = LinkageEntity::query()
                ->asArray()
                ->get('id', 'name');
            return array_map(function($item) {
                return [
                    'name' => $item['name'],
                    'next' => $item['id']
                ];
            }, $items);
        }
        $model = LinkageEntity::find(end($step));
        if (empty($model) || !$model->uri_template) {
            throw new GlobalException('未配置网址模板');
        }
        $items = LinkageDataEntity::query()
                ->where('linkage_id', $model->id)
                ->when(!empty($keywords), function ($query) {
                    SearchModel::searchWhere($query, ['name']);
                })
                ->orderBy('position', 'asc')
                ->asArray()
                ->get('id', 'name');
        return array_map(function($item) use ($model) {
            return [
                'name' => $item['name'], //sprintf('%s(%s)', $item['name'], $model['name']),
                'value' => SiteRepository::encodeUrl(self::site(), str_replace('{id}', (string)$item['id'], $model->uri_template))
            ];
        }, $items);
    }
}