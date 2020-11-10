<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Repositories\CategoryRepository;
use Zodream\Route\Controller\RestController;

class CategoryController extends RestController {

    public function indexAction() {
        return $this->renderData(CategoryRepository::getList());
    }

    public function allAction() {
        return $this->renderData(CategoryRepository::all());
    }
}