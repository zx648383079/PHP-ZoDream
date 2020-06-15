<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render([
            'name' => 'ZoDream Shop',
            'version' => '0.1',
            'logo' => url()->asset('assets/images/wap_logo.png'),
            'category' => CategoryModel::query()->count(),
            'brand' => BrandModel::query()->count(),
            'goods' => GoodsModel::query()->count(),
            'currency' => '￥',
        ]);
    }
}