<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\Admin\CategoryRepository;
use Zodream\Html\Tree;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction() {
        return $this->renderData(
            CategoryRepository::all(true)
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
                'name' => 'required|string:0,100',
                'keywords' => 'string:0,200',
                'description' => 'string:0,200',
                'icon' => 'string:0,200',
                'parent_id' => 'int',
                'position' => 'int:0,999',
                'banner' => 'string:0,200',
                'app_banner' => 'string:0,200',
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

    public function refreshAction() {
        CategoryRepository::refresh();
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