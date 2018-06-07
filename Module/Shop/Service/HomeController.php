<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\ArticleModel;

class HomeController extends Controller {

    public function indexAction() {
        return $this->redirect('./mobile');
    }
}