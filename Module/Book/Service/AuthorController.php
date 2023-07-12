<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;

class AuthorController extends Controller {

    public function indexAction(int $id) {
        $author = BookAuthorModel::find($id);
        $book_list = BookModel::ofClassify()->isOpen()->where('author_id',  $id)->get();
        return $this->show(compact('author', 'book_list',));
    }
}