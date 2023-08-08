<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api;

use Module\Catering\Domain\Repositories\ProductRepository;

class ProductController extends Controller {
	public function indexAction(int $store, string $keywords = '',
                                int $category = 0) {
        return $this->renderPage(
            ProductRepository::getList($store, $keywords, $category)
        );
	}

    public function detailAction(int $store, int $id) {
        try {
            return $this->render(ProductRepository::get($store, $id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }
}