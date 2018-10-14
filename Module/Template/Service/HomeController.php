<?php
namespace Module\Template\Service;

use Module\ModuleController;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Page;

class HomeController extends ModuleController {

    public function indexAction($site = 0, $id = 0) {
        $model = PageModel::findByIdOrSite($id, $site);
        $page = new Page($model, false);
        return $page->render();
    }


}