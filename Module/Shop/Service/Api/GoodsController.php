<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Domain\Model\ModelHelper;
use Module\ModuleController;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Module\Shop\Domain\Repositories\SearchRepository;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                int $category = 0,
                                int $brand = 0,
                                string $keywords = '',
                                int $per_page = 20, string $sort = '', string $order = '', bool $filter = false) {

        if (is_numeric($id) && $id > 0) {
            return $this->infoAction(intval($id));
        }
        $page = GoodsRepository::search(ModelHelper::parseArrInt($id), $category, $brand, $keywords, $per_page, $sort, $order);
        $data = $page->toArray();
        if ($filter) {
            $data['filter'] = SearchRepository::filterItems([]);
        }
        return $this->render($data);
    }

    public function infoAction(int $id, int $region = 0) {
        $data = GoodsRepository::detail($id);
        if (empty($data)) {
            return $this->renderFailure('商品错误！');
        }
        return $this->render($data);
    }

    public function stockAction(int $id, int $region = 0) {
        try {
            return $this->render(
                GoodsRepository::stock($id, $region)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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
        return $this->render(
            GoodsRepository::homeRecommend()
        );
    }
}