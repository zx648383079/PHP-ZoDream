<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Setting;
use Zodream\Route\Controller\RestController;

class ThemeController extends RestController {

    public function indexAction() {
        $setting = new Setting();
        return $this->render($setting->load()->get());
    }

    public function saveAction() {
        $setting = new Setting();
        $setting->load()->set([])->save();
        return $this->render(true);
    }
}