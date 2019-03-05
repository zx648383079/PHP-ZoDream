<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\Scene\Book;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {


    public function indexAction($id = 0, $category = null, $keywords = null, $per_page = 20) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction($id);
        }
        $book_list  = Book::with('category', 'author')->ofClassify()
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })
            ->when(is_array($id), function ($query) use ($id) {
                $query->whereIn('id', $id);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })
            ->page($per_page);
        return $this->renderPage($book_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        $book = Book::with('category', 'author')->where('id', $id)->first();
        if (empty($book)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($book->toArray());
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