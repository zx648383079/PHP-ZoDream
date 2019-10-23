<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookHistoryModel;
use Zodream\Route\Controller\RestController;

class HistoryController extends RestController {

    public function indexAction() {
        $book_list = [];
        return $this->render($book_list);
    }

    public function recordAction($book, $chapter = 0, $progress = 0) {
        BookHistoryModel::log($book, $chapter, $progress);
        return $this->render(true);
    }
}