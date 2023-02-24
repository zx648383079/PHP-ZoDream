<?php
namespace Module\WeChat;

use Module\SEO\Domain\ISiteMapModule;
use Module\SEO\Domain\SiteMap;
use Module\WeChat\Domain\Migrations\CreateWeChatTables;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Service\PlatformController;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\Controller\ICustomRouteModule;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ISiteMapModule, ICustomRouteModule {


    public function getMigration() {
        return new CreateWeChatTables();
    }

    public function invokeRoute(string $path, HttpContext $context): null|string|Output {
        if (preg_match('#^/?platform/([^/]*)/message#', $path, $match)) {
            return $this->invokeController(
                PlatformController::class,
                'message', [
                    'openid' => $match[1]
                ]);
        }
        return null;
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $map->add(url('./emulate'), time());
        $items = MediaModel::where('type', MediaModel::TYPE_NEWS)->orderBy('id', 'desc')
            ->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./emulate/media', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}