<?php
namespace Module\CMS;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Scene\BaseScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\Scene\SingleScene;
use Module\Template\Domain\Model\Base\OptionModel;
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
        $theme = OptionModel::findCode('theme');
        if (!is_string($theme)) {
            $theme = 'default';
        }
        return $theme;
    }

    /**
     * @return BaseScene
     * @throws \Exception
     */
    public static function scene() {
        return app(SceneInterface::class);
    }
}