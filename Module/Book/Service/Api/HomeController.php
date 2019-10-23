<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookLogModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\Scene\Book;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    public function indexAction($id = 0, $category = null, $keywords = null, $top = null, $page = 1, $per_page = 20) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction($id);
        }
        $query = Book::with('category', 'author')->ofClassify()
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })
            ->when(is_array($id), function ($query) use ($id) {
                $query->whereIn('id', $id);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            });

        $book_list = !empty($top) ?
            BookLogModel::getPage($query, $top, $page, $per_page)
            : $query->page($per_page);
        return $this->renderPage($book_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        $book = Book::with('category', 'author')->where('id', $id)->first();
        if (empty($book)) {
            return $this->renderFailure('id 错误！');
        }
        $data = $book->toArray();
        $data['chapter_count'] = $book->chapter_count;
        $data['first_chapter'] = $book->first_chapter;
        $data['last_chapter'] = $book->last_chapter;
        return $this->render($data);
    }

    public function hotAction() {
        $data = BookModel::limit(4)->pluck('name');
        return $this->render($data);
    }

    public function suggestAction($keywords) {
        $data = BookModel::when(!empty($keywords), function ($query) {
            BookModel::search($query, 'name');
        })->limit(4)->pluck('name');
        return $this->render($data);
    }

}