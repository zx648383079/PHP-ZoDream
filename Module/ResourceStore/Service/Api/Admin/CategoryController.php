<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;

use Module\ResourceStore\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction() {
        return $this->renderData(
            CategoryRepository::all(true)
        );
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
              CategoryRepository::save($input->validate([
                  'id' => 'int',
                  'name' => 'required|string:0,40',
                  'parent_id' => 'int',
                  'keywords' => 'string:0,255',
                  'description' => 'string:0,255',
                  'thumb' => 'string:0,255',
              ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        CategoryRepository::remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            CategoryRepository::all(false)
        );
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(CategoryRepository::search($keywords, $id));
    }
}