<?php
namespace Module\Shop\Service\Api;

use Domain\Model\ModelHelper;
use Module\ModuleController;
use Module\Shop\Domain\Repositories\GoodsRepository;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                $category = 0,
                                $brand = 0,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {

        if (is_numeric($id) && $id > 0) {
            return $this->infoAction($id);
        }
        $page = GoodsRepository::search(ModelHelper::parseArrInt($id), $category, $brand, $keywords, $per_page, $sort, $order);
        return $this->renderPage($page);
    }

    public function infoAction($id) {
        $data = GoodsRepository::detail($id);
        if (empty($data)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($data);
    }

    public function recommendAction($id) {
        $goods_list = GoodsRepository::getRecommendQuery('is_best')->limit(3)->all();
        return $this->render($goods_list);
    }

    public function hotAction($id) {
        $goods_list = GoodsRepository::getRecommendQuery('is_hot')->limit(7)->get();
        return $this->render($goods_list);
    }

    public function homeAction() {
        $hot_products = GoodsRepository::getRecommendQuery('is_hot')->all();
        $new_products = GoodsRepository::getRecommendQuery('is_new')->all();
        $best_products = GoodsRepository::getRecommendQuery('is_best')->all();
        return $this->render(compact('hot_products', 'new_products', 'best_products'));
    }
}