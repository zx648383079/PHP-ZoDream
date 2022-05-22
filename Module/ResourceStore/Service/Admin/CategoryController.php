<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Admin;

use Module\ResourceStore\Domain\Models\CategoryModel;
use Module\ResourceStore\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction() {
        $items = CategoryRepository::levelTree();
        return $this->show(compact('items'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = CategoryModel::findOrNew($id);
        $cat_list = CategoryRepository::levelTree($id > 0 ? [$id] : []);
        return $this->show('edit', compact('model', 'cat_list'));
    }

    public function saveAction(Input $input) {
        try {
            CategoryRepository::save($input->validate([
                'id' => 'int',
                'name' => 'required|string:0,40',
                'parent_id' => 'int',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'thumb' => 'string:0,255',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }

    public function deleteAction(int $id) {
        try {
            CategoryRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }
}