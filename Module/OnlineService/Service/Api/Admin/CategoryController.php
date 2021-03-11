<?php
declare(strict_types=1);
namespace Module\OnlineService\Service\Api\Admin;

use Module\OnlineService\Domain\Repositories\CategoryRepository;
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

    public function userAction(int $category = 0, string $keywords = '') {
        return $this->renderPage(
            CategoryRepository::userList($category, $keywords)
        );
    }

    public function userAddAction(int $category, int|array $user) {
        try {
            CategoryRepository::userAdd($category, $user);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function userDeleteAction(int $category, int|array $user) {
        try {
            CategoryRepository::userRemove($category, $user);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function wordAction(int $category = 0, string $keywords = '') {
        return $this->renderPage(
            CategoryRepository::wordList($category, $keywords)
        );
    }

    public function wordSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'content' => 'required|string:0,255',
                'cat_id' => 'required|int',
            ]);
            return $this->render(
                CategoryRepository::wordSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function wordDeleteAction(int $id) {
        try {
            CategoryRepository::wordRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function wordAllAction() {
         return $this->renderData(
             CategoryRepository::wordAll()
         );
    }
}