<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\Scene\Book;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {


    public function indexAction($id = 0, $category = null, $keywords = null, $per_page = 20) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $book_list  = Book::with('category', 'author')->ofClassify()->when(!empty($keywords), function ($query) {
                BookModel::search($query, ['name']);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when(!empty($keywords), function ($query) {
                BlogModel::search($query, ['title']);
            })
            ->page($per_page);
        return $this->renderPage($book_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        $book = BookModel::find($id);
        if (empty($book)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($book->toArray());
    }

}