<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api;

use Module\TradeTracker\Domain\Repositories\ProductRepository;

class ProductController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, int $project = 0) {
        return $this->renderPage(
            ProductRepository::getProductList($keywords, $category, $project)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(ProductRepository::get($id));
        } catch (\Throwable $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function priceAction(int $id) {
        try {
            return $this->render(ProductRepository::getPrice($id));
        } catch (\Throwable $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function chartAction(int $id, int $channel, int $type = 0,
                                string $startAt = '', string $endAt = '') {
        try {
            return $this->renderData(ProductRepository::getPriceList($id, $channel, $type, $startAt, $endAt));
        } catch (\Throwable $ex) {
            return $this->renderFailure($ex);
        }
    }
    public function suggestAction(string $keywords) {
        return $this->renderData(ProductRepository::suggestion($keywords));
    }

}