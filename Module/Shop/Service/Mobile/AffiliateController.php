<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;
use Zodream\Image\QrCode;

class AffiliateController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function qrAction() {
        $image = new QrCode();
        $image->encode($this->getUrl('', ['u' => auth()->id()]));
        return app('response')->image($image);
    }
}