<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\ThemeManager;
use Zodream\Helpers\Json;
use Zodream\Image\ImageStatic;

class ThemeController extends Controller {
    public function indexAction() {
        $current = CMSRepository::theme();
        $themes = (new ThemeManager)->getAllThemes();
        foreach ($themes as $key => $item) {
            if ($item['name'] === $current) {
                $current = $item;
                //unset($themes[$key]);
                break;
            }
        }
        return $this->show(compact('themes', 'current'));
    }

    public function marketAction() {
        return $this->show();
    }

    public function applyAction($theme) {
        (new ThemeManager())->apply($theme);
        return $this->renderData([
            'url' => $this->getUrl('theme')
        ]);
    }

    public function backAction() {
        (new ThemeManager())->pack();
        return $this->renderData([
            'url' => $this->getUrl('theme')
        ]);
    }

    public function installAction() {
    }

    public function coverAction($theme) {
        $manager = new ThemeManager();
        $folder = $manager->getSrc()->directory($theme);
        $data = $folder->file('theme.json')->read();
        $data = Json::decode($data);
        return app('response')->image(ImageStatic::make($folder->file($data['cover'])));
    }
}