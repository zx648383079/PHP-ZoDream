<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\Admin\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class CategoryController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(CategoryRepository::getList($keywords));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'icon' => 'string:0,255',
                'parent_id' => 'int',
            ]);
            return $this->render(CategoryRepository::save($data));
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
        return $this->renderData(CategoryRepository::all());
    }
}