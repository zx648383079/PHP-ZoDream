<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Service\Controller;

class HistoryController extends Controller {

    public function indexAction() {
        $book_list = [];
        return $this->show(compact('book_list'));
    }
}