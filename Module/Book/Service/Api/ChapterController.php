<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\BookRepository;

class ChapterController extends Controller {

    public function indexAction(int $id = 0, int $book = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        try {
            BookRepository::checkOpen($book);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(BookRepository::chapters($book));
    }

    public function detailAction(int $id, int $book = 0) {
        try {
            $model = BookRepository::chapter($id, $book);
            BookRepository::checkOpen($model['book_id']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}