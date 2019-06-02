<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\Scene\Goods;
use Module\Shop\Domain\Repositories\GoodsRepository;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                $category = 0,
                                $brand = 0,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {
        if (!is_array($id) && $id > 0) {
            return $this->infoAction($id);
        }
        $page = GoodsRepository::searchComplete(!is_array($id) ? [] : $id, $category, $brand, $keywords, $per_page, $sort, $order);
        return $this->renderPage($page);
    }

    public function infoAction($id) {
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($goods);
    }
}