<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\BookRepository;

class ChapterController extends Controller {

    public function indexAction($id = 0, $book = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $chapter_list  = BookRepository::chapters($book);
        return $this->renderData($chapter_list);
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