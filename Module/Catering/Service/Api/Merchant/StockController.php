<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\CategoryRepository;
use Module\Catering\Domain\Repositories\StockRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class StockController extends Controller {

	public function indexAction(string $keywords = '') {
        return $this->renderPage(StockRepository::merchantList($keywords));
	}

    public function orderAction(string $keywords = '') {
        return $this->renderPage(StockRepository::merchantOrderList($keywords));
    }

    public function categoryAction() {
        return $this->renderData(CategoryRepository::merchantList(CategoryRepository::TYPE_STOCK));
    }

    public function categorySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
            ]);
            return $this->render(
                CategoryRepository::merchantSave(CategoryRepository::TYPE_STOCK, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function categoryDeleteAction(int $id) {
        try {
            CategoryRepository::merchantRemove(CategoryRepository::TYPE_STOCK, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}