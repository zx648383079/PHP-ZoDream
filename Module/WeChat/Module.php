<?php
namespace Module\WeChat;

use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Migrations\CreateWeChatTables;
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
}