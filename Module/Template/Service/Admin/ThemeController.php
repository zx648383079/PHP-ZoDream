<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\ThemeModel;

use Zodream\Route\Controller\Controller;

class ThemeController extends Controller {

    public function indexAction() {

    }


    public function installAction() {
        $data = ThemeModel::findTheme();
        foreach ($data as $item) {
            ThemeModel::install($item);
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}