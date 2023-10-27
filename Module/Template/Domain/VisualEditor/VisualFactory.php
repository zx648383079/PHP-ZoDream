<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Domain\Providers\MemoryCacheProvider;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Repositories\PageRepository;
use Zodream\Disk\Directory;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Template\ViewFactory;

class VisualFactory {
    protected static array $lockData = [];

    public static function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    /**
     * 清除锁定数据
     * @return void
     */
    public static function unlock(): void {
        static::$lockData = [];
    }

    /**
     * 锁定数据
     * @param int $parentId
     * @param int $index
     * @return void
     * @throws \Exception
     */
    public static function lock(int $parentId, int $index): void {
        if (isset(static::$lockData[$parentId][$index])) {
            throw new \Exception(sprintf('weight[%d-%d] is loop die', $parentId, $index));
        }
        static::$lockData[$parentId][$index] = 1;
    }

    public static function newViewFactory(?bool $cacheable = null): ViewFactory {
        $factory = new ViewFactory();
        $factory->setEngine(ParserCompiler::class, $cacheable)
            ->setConfigs([
                'suffix' => VisualPage::EXT
            ])
            ->getEngine()
            ->registerFunc('weight', '$'.VisualPage::ENGINE_KEY.'->weight');
        return $factory;
    }

    /**
     * @return BaseWeight
     */
    public static function newWeight(string $name, string $fileName): mixed {
        if (class_exists($fileName)) {
            return new $fileName;
        }
        if (!file_exists($fileName)) {
            $fileName = (string)VisualFactory::templateFolder($fileName);
        }
        if (is_dir($fileName)) {
            $fileName .= '/weight.php';
        }
        include_once $fileName;
        $name = Str::studly($name).'Weight';
        return (new $name)->setDirectory(dirname($fileName));
    }

    /**
     *
     * @param string $name
     * @param string $fileName
     * @return IVisualStyle
     */
    public static function newStyle(string $name, string $fileName): mixed {
        if (class_exists($fileName)) {
            return new $fileName;
        }
        if (!file_exists($fileName)) {
            $fileName = (string)VisualFactory::templateFolder($fileName);
        }
        if (is_dir($fileName)) {
            $fileName .= '/style.php';
        }
        include_once $fileName;
        $name = Str::studly($name).'Style';
        return new $name;
    }

    /**
     * 获取模板路径
     * @param string $path
     * @return bool|FileObject
     */
    public static function templateFolder(string $path = ''): mixed {
        $folder = new Directory(dirname(dirname(__DIR__)).'/UserInterface/templates');
        if (empty($path)) {
            return $folder;
        }
        return $folder->child($path);
    }

    /**
     * 根据id进入页面
     * @param int $site
     * @param int $id
     * @return VisualPage
     * @throws \Exception
     */
    public static function entry(int $site, int $id = 0): VisualPage {
        $siteModel = SiteModel::findOrThrow($site);
        if ($siteModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('site not publish');
        }
        if ($id > 0) {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('id', $id)->first();
        } else if ($siteModel->default_page_id > 0) {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('id', $siteModel->default_page_id)->first();
        } else {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('status', PageRepository::PUBLISH_STATUS_POSTED)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')->first();
        }
        if (empty($pageModel)) {
            throw new \Exception('page not found');
        }
        if ($pageModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('page not publish');
        }
        return new VisualPage($siteModel, $pageModel, false);
    }

    /**
     * 根据域名路径进入页面
     * @param string $domain
     * @param string $path
     * @return VisualPage
     * @throws \Exception
     */
    public static function entryRewrite(string $domain, string $path = ''): VisualPage {
        $siteModel = SiteModel::where('domain', $domain)->first();
        if (empty($siteModel)) {
            throw new \Exception('page not found');
        }
        if ($siteModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('site not publish');
        }
        if (!empty($path)) {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('name', $path)->first();
        } else if ($siteModel->default_page_id > 0) {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('id', $siteModel->default_page_id)->first();
        } else {
            $pageModel = SitePageModel::where('site_id', $siteModel->id)
                ->where('status', PageRepository::PUBLISH_STATUS_POSTED)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')->first();
        }
        if (empty($pageModel)) {
            throw new \Exception('page not found');
        }
        if ($pageModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('page not publish');
        }
        return new VisualPage($siteModel, $pageModel, false);
    }

    /**
     * 缓存或输出
     * @param string $key
     * @param callable $cb function(): VisualPage
     * @return Output
     */
    public static function cachePage(string $key, callable $cb): Output {
        $request = request();
        $response = response();
        if ($request->method() !== 'GET') {
            $renderer = call_user_func($cb);
            return $response->html($renderer->render());
        }
        $cacheKey = sprintf('%s?%s', $key, $_SERVER['QUERY_STRING']??'');
        $cacheDriver = cache()->store('pages');
        if (($page = $cacheDriver->get($cacheKey)) !== false) {
            return $response->html($page);
        }
        /** @var VisualPage $renderer */
        $renderer = call_user_func($cb);
        $page = $renderer->render();
        $cacheTime = $renderer->cacheTime();
        if ($cacheTime > 0) {
            $cacheDriver->set($key, $page, $cacheTime);
        }
        return $response->html($page);
    }
}