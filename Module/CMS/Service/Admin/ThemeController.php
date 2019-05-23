<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\ThemeManager;
use Module\CMS\Module;

class ThemeController extends Controller {
    public function indexAction() {
        $current = Module::theme();
        $themes = (new ThemeManager)->getAllThemes();
        foreach ($themes as $key => $item) {
            if ($item['name'] === $current) {
                $current = $item;
                unset($themes[$key]);
                break;
            }
        }
        dd($current, $themes);
        return $this->show(compact('themes', 'current'));
    }

    public function marketAction() {
        return $this->show();
    }

    public function applyAction() {
    }

    public function installAction() {
    }
}