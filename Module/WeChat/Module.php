<?php
namespace Module\WeChat;

use Module\SEO\Domain\SiteMap;
use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Migrations\CreateWeChatTables;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Service\PlatformController;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function boot() {
        app()->register(MessageReply::class);
    }

    public function getMigration() {
        return new CreateWeChatTables();
    }

    public function invokeRoute($path) {
        if (preg_match('#^/?platform/([^/]*)/message#', $path, $match)) {
            return $this->invokeController(
                PlatformController::class,
                'message', [
                    'openid' => $match[1]
                ]);
        }
    }

    /**
     * @return MessageReply
     * @throws \Exception
     */
    public static function reply() {
        return app(MessageReply::class);
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