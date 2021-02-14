<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            CategoryRepository::getList($keywords)
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
                'icon' => 'string:0,200',
                'description' => 'string:0,255',
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
}