<?php
namespace Module\Book\Service\Api;

use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\BookRepository;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    public function indexAction(
        $id = 0, $category = null, $keywords = null, $top = null, $over = false, $page = 1, $per_page = 20) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction($id);
        }
        $book_list = BookRepository::getList($id, $category, $keywords, $top, $over, $page, $per_page);
        return $this->renderPage($book_list);
    }

    public function detailAction($id) {
        $id = intval($id);
        $book = BookModel::with('category', 'author')->where('id', $id)->first();
        if (empty($book)) {
            return $this->renderFailure('id 错误！');
        }
        return $this->render($book);
    }

    public function hotAction() {
        $data = BookModel::limit(4)->pluck('name');
        return $this->render($data);
    }

    public function suggestAction($keywords) {
        $data = BookModel::when(!empty($keywords), function ($query) {
            BookModel::searchWhere($query, 'name');
        })->limit(4)->pluck('name');
        return $this->render($data);
    }

}