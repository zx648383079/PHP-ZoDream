<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\ChapterRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class BookController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, int $author = 0, int $classify = 0) {
        return $this->renderPage(
            BookRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                BookRepository::detail($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'cover' => 'string:0,200',
                'description' => 'string:0,200',
                'author_id' => 'int',
                'classify' => 'int:0,127',
                'cat_id' => 'int:0,127',
                'size' => 'int',
                'over_at' => 'int',
                'source' => 'string:0,200',
            ]);
            return $this->render(
                BookRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            BookRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function chapterAction(int $book, string $keywords = '') {
        return $this->renderPage(
            ChapterRepository::getList($book, $keywords)
        );
    }

    public function chapterDetailAction(int $id) {
        try {
            return $this->render(
                ChapterRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chapterSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'book_id' => 'required|int',
                'title' => 'required|string:0,200',
                'parent_id' => 'int',
                'price' => 'int',
                'position' => 'int:0,127',
                'size' => 'int',
                'source' => 'string:0,200',
                'content' => 'required|string'
            ]);
            return $this->render(
                ChapterRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chapterDeleteAction(int $id) {
        try {
            ChapterRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function refreshAction() {
        try {
            BookRepository::refreshBook();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}