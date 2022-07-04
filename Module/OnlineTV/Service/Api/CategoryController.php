<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api;

use Module\OnlineTV\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction(int $parent = 0, string $extra = '') {
        return $this->renderData(CategoryRepository::getChildren($parent, $extra));
    }

    public function levelAction() {
        return $this->renderData(CategoryRepository::levelTree());
    }

    public function treeAction() {
        return $this->renderData(CategoryRepository::tree());
    }
}