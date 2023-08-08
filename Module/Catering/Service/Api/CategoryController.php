<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api;

use Module\Catering\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {
	public function indexAction(int $store) {
        return $this->renderData(
            CategoryRepository::getList($store)
        );
	}
}