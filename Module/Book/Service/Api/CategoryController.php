<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction() {
        return $this->renderData(CategoryRepository::getList());
    }

    public function allAction() {
        return $this->renderData(CategoryRepository::all());
    }
}