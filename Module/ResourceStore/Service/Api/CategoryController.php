<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api;

use Module\ResourceStore\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction(int $parent = 0) {
        return $this->renderData(CategoryRepository::getChildren($parent));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                CategoryRepository::getFull($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function levelAction() {
        return $this->renderData(CategoryRepository::levelTree());
    }

    public function treeAction() {
        return $this->renderData(CategoryRepository::tree());
    }
}