<?php
namespace Module\CMS;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Scene\BaseScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\Scene\SingleScene;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function boot() {
        app()->register(SceneInterface::class, SingleScene::class);
    }

    public function getMigration() {
        return new CreateCmsTables();
    }

    /**
     * @return BaseScene
     * @throws \Exception
     */
    public static function scene() {
        return app(SceneInterface::class);
    }
}