<?php
namespace Module\Template\Service;

use Module\ModuleController;
use Module\Template\Domain\Page;

class PageController extends ModuleController {

    public function indexAction($name = 'index') {
        return new Page($name);
    }


}