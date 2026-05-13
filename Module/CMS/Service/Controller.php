<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\ModuleController;

class Controller extends ModuleController {

    public function prepare() {
        CMSRepository::registerView();
        $categories_tree = FuncHelper::channels(['tree' => true]);
        $language = FuncHelper::option('language');
        $this->send(compact('categories_tree', 'language'));
    }
}