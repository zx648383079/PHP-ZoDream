<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

	public function indexAction(int $type = 0) {
        return $this->renderData(CategoryRepository::merchantList($type));
	}

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'type' => 'int',
                'name' => 'required|string:0,20',
            ]);
            return $this->render(
                CategoryRepository::merchantSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function deleteAction(int $id) {
        try {
            CategoryRepository::merchantRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}