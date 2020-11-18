<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\BookRepository;
use Zodream\Route\Controller\RestController;

class ChapterController extends RestController {

    public function indexAction($id = 0, $book = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $chapter_list  = BookRepository::chapters($book);
        return $this->renderPage($chapter_list);
    }

    public function detailAction($id, $book = 0) {
        try {
            $model = BookRepository::chapter($id, $book);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}