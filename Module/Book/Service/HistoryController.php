<?php
namespace Module\Book\Service;


class HistoryController extends Controller {

    public function indexAction() {
        $book_list = [];
        return $this->show(compact('book_list'));
    }
}