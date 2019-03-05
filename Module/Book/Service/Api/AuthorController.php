<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookAuthorModel;
use Zodream\Route\Controller\RestController;

class AuthorController extends RestController {

    public function indexAction() {
        return $this->render(BookAuthorModel::all());
    }
}