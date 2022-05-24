<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api;

use Module\AppStore\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction(int $parent = 0) {
        return $this->render(CategoryRepository::getChildren($parent));
    }

    public function levelAction() {
        return $this->render(CategoryRepository::levelTree());
    }

    public function treeAction() {
        return $this->render(CategoryRepository::tree());
    }
}