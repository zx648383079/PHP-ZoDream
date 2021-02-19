<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Book\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction() {
        return $this->renderData(
            CategoryRepository::getList()
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                CategoryRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string',
            ]);
            return $this->render(
                CategoryRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            CategoryRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            CategoryRepository::all()
        );
    }
}