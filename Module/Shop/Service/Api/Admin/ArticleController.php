<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\ArticleRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ArticleController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0) {
        return $this->renderPage(
            ArticleRepository::getList($keywords, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ArticleRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'cat_id' => 'required|int',
                'title' => 'required|string:0,100',
                'keywords' => 'string:0,200',
                'thumb' => 'string:0,200',
                'description' => 'string:0,200',
                'brief' => 'string:0,200',
                'url' => 'string:0,200',
                'file' => 'string:0,200',
                'content' => 'required',
            ]);
            return $this->render(
                ArticleRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ArticleRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function categoryAction(string $keywords = '') {
        return $this->renderPage(
            ArticleRepository::categoryList($keywords)
        );
    }

    public function detailCategoryAction(int $id) {
        try {
            return $this->render(
                ArticleRepository::category($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveCategoryAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'keywords' => 'string:0,200',
                'description' => 'string:0,200',
                'parent_id' => 'int',
                'position' => 'int:0,999',
            ]);
            return $this->render(
                ArticleRepository::categorySave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteCategoryAction(int $id) {
        try {
            ArticleRepository::categoryRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function categoryTreeAction() {
        return $this->renderData(ArticleRepository::categoryAll());
    }
}