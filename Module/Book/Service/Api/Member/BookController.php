<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Member;

use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\ChapterRepository;
use Module\Book\Domain\Repositories\RoleRepository;
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

    public function moveAction(int $id, int $before = 0, int $after = 0) {
        try {
            ChapterRepository::move($id, $before, $after);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function refreshAction(int $id) {
        try {
            BookRepository::refreshPosition($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function chapterDeleteAction(int $id) {
        try {
            ChapterRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function roleAction(int $book) {
        if (BookRepository::isSelf($book)) {
            return $this->renderFailure('book is error');
        }
        return $this->render(
            RoleRepository::all($book)
        );
    }

    public function roleSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'book_id' => 'required|int',
                'name' => 'required|string:0,50',
                'avatar' => 'string:0,200',
                'description' => 'string:0,200',
                'character' => 'string:0,20',
                'x' => 'string:0,20',
                'y' => 'string:0,20',
                'link_id' => 'int',
                'link_title' => 'string'
            ]);
            return $this->render(
                RoleRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function roleDeleteAction(int $id) {
        try {
            RoleRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function linkAddAction(int $from, int $to, string $title = '') {
        try {
            RoleRepository::linkAdd(RoleRepository::bookId($from, $to), $from, $to, $title);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function linkSaveAction(int $book, array $items) {
        if (BookRepository::isSelf($book)) {
            return $this->renderFailure('book is error');
        }
        try {
            RoleRepository::linkSave($book, $items);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function linkDeleteAction(int $from, int $to) {
        try {
            RoleRepository::linkRemove(RoleRepository::bookId($from, $to), $from, $to);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function mapAction(int $book) {
        if (BookRepository::isSelf($book)) {
            return $this->renderFailure('book is error');
        }
        return $this->renderData(RoleRepository::mapGet($book));
    }

    public function mapSaveAction(int $book, array $items) {
        if (BookRepository::isSelf($book)) {
            return $this->renderFailure('book is error');
        }
        try {
            RoleRepository::mapSave($book, $items);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function eventAction(int $book, string $keywords = '', string $time = '') {
        if (BookRepository::isSelf($book)) {
            return $this->renderFailure('book is error');
        }
        return $this->renderPage(RoleRepository::eventList($book, $keywords, $time));
    }

    public function eventSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'book_id' => 'required|int',
                'name' => 'required|string:0,200',
                'location' => 'string:0,200',
                'role' => 'string:0,200',
                'time_node' => '',
                'start_at' => 'datetime',
                'end_at' => 'datetime',
            ]);
            return $this->render(
                RoleRepository::eventSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function eventDeleteAction(int $id) {
        try {
            RoleRepository::eventRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}