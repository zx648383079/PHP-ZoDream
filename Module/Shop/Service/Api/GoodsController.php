<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Domain\Model\ModelHelper;
use Module\ModuleController;
use Module\Shop\Domain\Repositories\GoodsRepository;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                int $category = 0,
                                int $brand = 0,
                                string $keywords = '',
                                int $per_page = 20, string $sort = '', string $order = '') {

        if (is_numeric($id) && $id > 0) {
            return $this->infoAction(intval($id));
        }
        $page = GoodsRepository::search(ModelHelper::parseArrInt($id), $category, $brand, $keywords, $per_page, $sort, $order);
        return $this->renderPage($page);
    }

    public function infoAction(int $id) {
        $data = GoodsRepository::detail($id);
        if (empty($data)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($data);
    }

    public function recommendAction(int $id) {
        $goods_list = GoodsRepository::getRecommendQuery('is_best')->limit(3)->all();
        return $this->render($goods_list);
    }

    public function hotAction(int $id) {
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