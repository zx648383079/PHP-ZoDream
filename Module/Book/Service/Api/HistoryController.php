<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\HistoryRepository;

class HistoryController extends Controller {

    public function indexAction() {
        return $this->renderPage(HistoryRepository::getHistory());
    }

    public function recordAction(int $book, int $chapter = 0, float $progress = 0) {
        HistoryRepository::record($book, $chapter, $progress);
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        HistoryRepository::removeBook($id);
        return $this->renderData(true);
    }
}