<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;
use Zodream\Image\QrCode;

class RefundController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function createAction($order, $goods = 0) {
        return $this->show();
    }
}