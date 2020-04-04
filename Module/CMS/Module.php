<?php
namespace Module\CMS;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Scene\BaseScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\Scene\SingleScene;
use Module\SEO\Domain\Option;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function boot() {
        app()->register(SceneInterface::class, SingleScene::class);
    }

    public function getMigration() {
        return new CreateCmsTables();
    }

    public static function theme() {
        static $theme;
        if (is_string($theme)) {
            return $theme;
        }
        $preview = app('request')->get('preview');
        if (!empty($preview)) {
            return $preview;
        }
        return Option::value('theme', 'default');
    }

    /**
     * @return BaseScene
     * @throws \Exception
     */
    public static function scene() {
        return app(SceneInterface::class);
    }
}