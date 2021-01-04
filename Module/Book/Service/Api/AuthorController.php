<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookAuthorModel;

class AuthorController extends Controller {

    public function indexAction() {
        return $this->render(BookAuthorModel::all());
    }
}