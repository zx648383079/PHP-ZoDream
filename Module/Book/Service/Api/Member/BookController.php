<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Member;

use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\ChapterRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class BookController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0) {
        return $this->renderPage(BookRepository::getSelfList($keywords, $category));
    }

    public function detailAction(int $id) {
        return $this->render(BookRepository::getSelf($id));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'cover' => 'string:0,200',
                'description' => 'string:0,200',
            ]);
            return $this->render(
                BookRepository::saveSelf($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function overAction(int $id) {
        try {
            return $this->render(
                BookRepository::overSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chapterDetailAction(int $id) {
        try {
            return $this->render(
                ChapterRepository::getSelf($id)
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
                'position' => 'int',
                'type' => 'int:0,127',
                'content' => 'string',
                'size' => 'int',
            ]);
            return $this->render(
                ChapterRepository::saveSelf($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function chapterDeleteAction(int $id) {
        try {
            ChapterRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}