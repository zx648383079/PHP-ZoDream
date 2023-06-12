<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Domain\Model\ModelHelper;
use Module\ModuleController;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Module\Shop\Domain\Repositories\IssueRepository;
use Module\Shop\Domain\Repositories\SearchRepository;

class GoodsController extends Controller {

    public function rules() {
        return [
            'ask' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id = 0,
                                int $category = 0,
                                int $brand = 0,
                                string $keywords = '',
                                string $price = '',
                                int $per_page = 20, string $sort = '', string $order = '', bool $filter = false) {

        if (is_numeric($id) && $id > 0) {
            return $this->infoAction(intval($id));
        }
        $page = GoodsRepository::search(ModelHelper::parseArrInt($id), $category,
            $brand, $keywords, $per_page, $sort, $order, $price);
        $data = $page->toArray();
        if ($filter) {
            $data['filter'] = SearchRepository::filterItems($keywords, $category, $brand);
        }
        return $this->render($data);
    }

    public function infoAction(int $id = 0, int $product = 0, int $region = 0) {
        try {
            $data = GoodsRepository::detail($id, true, $product);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if (empty($data)) {
            return $this->renderFailure('商品不存在');
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

    public function priceAction(int $id, $properties = null, int $amount = 1, int $region = 0) {
        try {
            $properties = AttributeRepository::formatPostProperties($properties);
            return $this->render(
                GoodsRepository::price($id, $properties, $amount, $region)
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

    public function issueAction(int $item_id, string $keywords = '') {
        return $this->renderPage(
            IssueRepository::getList($item_id, $keywords)
        );
    }

    public function issueAskAction(int $item_id, string $content) {
        try {
            IssueRepository::create($item_id, $content);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}