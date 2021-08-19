<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\BookRepository;

class HomeController extends Controller {

    public function indexAction(
        int|array $id = 0, int $category = 0,
        string $keywords = '', bool $top = false,
        int $status = 0, int $author = 0,
        int $page = 1, int $per_page = 20) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction(intval($id));
        }
        $book_list = BookRepository::getList($id, $category, $keywords, $top, $status, $author, $page, $per_page);
        return $this->renderPage($book_list);
    }

    public function detailAction(int $id) {
        try {
            $model = BookRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function hotAction() {
        return $this->renderData(
            BookRepository::getHot()
        );
    }

    public function suggestAction(string $keywords) {
        return $this->renderData(
            BookRepository::suggestion($keywords)
        );
    }

}