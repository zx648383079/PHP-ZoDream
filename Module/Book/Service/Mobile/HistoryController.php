<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Repositories\HistoryRepository;
use Module\Book\Service\Controller;

class HistoryController extends Controller {

    public function indexAction() {
        $chapter_list = HistoryRepository::getHistory();
        return $this->show(compact('chapter_list'));
    }
}