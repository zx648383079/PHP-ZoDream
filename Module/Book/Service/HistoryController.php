<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookModel;

class HistoryController extends Controller {

    public function indexAction() {
        $book_list = [];
        return $this->show(compact('book_list'));
    }
}