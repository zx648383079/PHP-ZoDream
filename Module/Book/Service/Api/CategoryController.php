<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookCategoryModel;
use Zodream\Route\Controller\RestController;

class CategoryController extends RestController {

    public function indexAction() {
        return $this->render(BookCategoryModel::all());
    }
}