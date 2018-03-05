<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\ArticleModel;

class HomeController extends Controller {

    public function indexAction() {
        $latest_article = ArticleModel::with('category')->where('cat_id', 1)->limit(5)->all();
        return $this->show(compact('latest_article'));
    }
}