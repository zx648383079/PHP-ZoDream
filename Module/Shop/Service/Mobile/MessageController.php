<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;

class MessageController extends Controller {

    public function indexAction() {
        $model_list = [1];
        return $this->show(compact('model_list'));
    }

    public function detailAction($id) {
        return $this->show();
    }
}