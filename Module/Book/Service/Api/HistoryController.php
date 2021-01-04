<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\HistoryRepository;

class HistoryController extends Controller {

    public function indexAction() {
        $book_list = HistoryRepository::getHistory();
        return $this->renderPage($book_list);
    }

    public function recordAction($book, $chapter = 0, $progress = 0) {
        HistoryRepository::record($book, $chapter, $progress);
        return $this->renderData(true);
    }

    public function deleteAction($id) {
        HistoryRepository::removeBook($id);
        return $this->renderData(true);
    }
}