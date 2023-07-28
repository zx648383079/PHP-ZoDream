<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;

class HomeController extends Controller {

    public function indexAction() {
        $title = FuncHelper::translate('首页');
        return $this->show(compact('title'));
    }
}