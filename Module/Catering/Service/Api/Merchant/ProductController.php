<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\CategoryRepository;
use Module\Catering\Domain\Repositories\ProductRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ProductController extends Controller {
	public function indexAction(string $keywords = '',
                                int $category = 0) {
        return $this->renderPage(
            ProductRepository::merchantList($keywords, $category)
        );
	}

    public function detailAction(int $id) {
        try {
            return $this->render(ProductRepository::merchantGet($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function categoryAction() {
        return $this->renderData(CategoryRepository::merchantList(CategoryRepository::TYPE_PRODUCT));
    }

    public function categorySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
            ]);
            return $this->render(
                CategoryRepository::merchantSave(CategoryRepository::TYPE_PRODUCT, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function categoryDeleteAction(int $id) {
        try {
            CategoryRepository::merchantRemove(CategoryRepository::TYPE_PRODUCT, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}