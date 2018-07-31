<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Service\Controller;

class AuthorController extends Controller {

    public function indexAction($id) {
        $author = BookAuthorModel::find($id);
        $book_list = BookModel::ofClassify()->where('author_id', $id)->all();
        return $this->show(compact('author', 'book_list'));
    }
}