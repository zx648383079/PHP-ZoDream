<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Middleware;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CacheRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Module;
use Module\CMS\Service\CategoryController;
use Module\CMS\Service\ContentController;
use Module\CMS\Service\HomeController;
use Zodream\Helpers\Str;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Infrastructure\Contracts\Route;
use Zodream\Route\OnlyRoute;
use Zodream\Service\Middleware\MiddlewareInterface;

/**
 * 增加优雅链接支持
 */
class CMSSeoMiddleware implements MiddlewareInterface{

    public function handle(HttpContext $context, callable $next) {
        $path = $context->path();
        if (str_starts_with($path, 'open/') || str_contains($path, '/admin/')) {
            return $next($context);
        }
        /** @var Input $request */
        $request = $context['request'];
        $site = CMSRepository::matchSite($request->scheme(), $request->host(), $path, true);
        if ($site < 1) {
            return $next($context);
        }
        $site = SiteModel::findOrThrow($site);
        CMSRepository::site($site);
        if ($site->language) {
            trans()->setLanguage($site->language)->reset();
        }
        $modulePath = trim(parse_url((string)$site['match_rule'], PHP_URL_PATH), '/');
        if ($modulePath !== '') {
            $path = trim(substr($path, strlen($modulePath)), '/');
        }
        if ($path === '') {
            return new OnlyRoute(HomeController::class, 'indexAction', [], [
                $modulePath => Module::class
            ]);
        }
        $args = explode('/', $path);
        return match ($args[0]) {
            'category' => $this->findChannel($args, $modulePath),
            'content' => $this->findArticle($args, $modulePath),
            'comment' or 'home' or 'form' => new OnlyRoute(sprintf('Module\\CMS\\Service\\%sController',
                Str::studly($args[0])), empty($args[1]) ? 'indexAction' :
                sprintf('%sAction', lcfirst(Str::studly($args[1]))), [], [
                $modulePath => Module::class
            ]),
            default => $next($context),
        };
    }

    private function findChannel(array $args, string $modulePath): Route {
        $action = '';
        $parameters = [];
        if (!empty($args[1])) {
            if ($args[1] === 'list') {
                $action = $args[1];
            }
            $id = $args[1] === 'list' && count($args) === 2 ? 0 :
                static::getChannelData(end($args), false);
            if ($id > 0) {
                $parameters = [
                    'id' => $id,
                ];
            }
        }
        return new OnlyRoute(CategoryController::class, empty($action) ? 'indexAction' :
            sprintf('%sAction', $action), $parameters, [
            $modulePath => Module::class
        ]);
    }

    private function findArticle(array $args, string $modulePath): Route {
        $parameters = [];
        if (count($args) > 1) {
            $item = static::getArticleData($args[1], false);
            if (!empty($item)) {
                $parameters = [
                    'id' => $item['id'],
                    'model' => $item['model_id'],
                    'category' => $item['cat_id']
                ];
            }
        }
        return new OnlyRoute(ContentController::class, 'indexAction', $parameters, [
            $modulePath => Module::class
        ]);
    }

    protected static function getChannelData(string $val, bool $isId = false): false|int|string {
        $maps = FuncHelper::cache()->getOrSet('mapId', 'all', function () {
            return CacheRepository::getMapCache();
        });
        if (empty($maps) || empty($maps['channel'])) {
            return false;
        }
        $maps = $maps['channel'];
        if ($isId) {
            return array_search($val, $maps);
        }
        return $maps[$val] ?? false;
    }

    protected static function getArticleData(string $val, bool $isId = false): ?array {
        $maps = FuncHelper::cache()->getOrSet('article_map', 1, function () {
            return CacheRepository::getSeoCache();
        });
        if (empty($maps)) {
            return null;
        }
        $key = $isId ? 'id' : 'seo_link';
        foreach ($maps as $item) {
            if ((string)$item[$key] === $val) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 编译链接
     * @param int|string|array $id 文章请传数组
     * @param bool $isArticle
     * @return string
     */
    public static function encodeUrl(int|string|array $id, bool $isArticle = true): string {
        if (!$isArticle) {
            $link = static::getChannelData((string)$id, true);
            if (empty($link)) {
                return static::formatUrl('category', ['id' => $id]);
            }
            return static::formatUrl(sprintf('category/%s', $link));
        }
        $data = $id;
        $id = (string)$id['id'];
        $item = static::getArticleData($id, true);
        if (empty($item)) {
            return static::formatUrl('content', [
                'id' => $id,
                'model' => $data['model_id'],
                'category' => $data['cat_id']
            ]);
        }
        if (empty($item['seo_link'])) {
            return static::formatUrl('content', [
                'id' => $id,
                'model' => $item['model_id'],
                'category' => $item['cat_id']
            ]);
        }
        return static::formatUrl(sprintf('content/%s', $item['seo_link']));
    }

    protected static function formatUrl(string $path, array $data = []): string {
        /** @var Uri $uri */
        $uri = clone FuncHelper::cache()->getOrSet(__FUNCTION__, CMSRepository::siteId(), function () {
            $site = CMSRepository::site();
            $uri = new Uri($site['match_rule']);
            $request = request();
            if (empty($uri->getScheme())) {
                $uri->setScheme($request->scheme());
            }
            if (empty($uri->getHost())) {
                $uri->setHost($request->host());
            }
            if (CMSRepository::isPreview()) {
                $uri->addData(CMSRepository::PREVIEW_KEY, $_GET[CMSRepository::PREVIEW_KEY]);
            }
            return $uri;
        });
        if (str_starts_with($path, './')) {
            $path = substr($path, 2);
        }
        if (!empty($path)) {
            $uri->addPath($path);
        }
        $uri->addData($data);
        return (string)url()->encode($uri);
    }
}