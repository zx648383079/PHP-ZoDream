<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookLogModel;
use Zodream\Route\Controller\RestController;

class ChapterController extends RestController {

    public function indexAction($id = 0, $book = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $chapter_list  = BookChapterModel::where('book_id', $book)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->all();
        return $this->render($chapter_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        $chapter = BookChapterModel::find($id);
        if (empty($chapter)) {
            return $this->renderFailure('id 错误！');
        }
        BookLogModel::logBook($chapter->book_id);
        $data = $chapter->toArray();
        $data['content'] = $chapter->body->content;
        $data['previous'] = $chapter->previous;
        $data['next'] = $chapter->next;
        return $this->render($data);
    }
}