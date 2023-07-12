<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\DownloadRepository;
use Module\Book\Domain\Repositories\HistoryRepository;
use Module\Book\Domain\Setting;

use Zodream\Infrastructure\Contracts\Http\Output;


class BookController extends Controller {

    public function rules() {
        return [
            'txt' => '@',
            'zip' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(int $id) {
        $book = BookModel::isOpen()->where('id', $id)->first();
        if (!$book) {
            return $this->redirectWithMessage('./', '书籍不存在');
        }
        if (auth()->guest() && $book->classify > 0) {
            return $this->redirectWithAuth();
        }
        $cat = BookCategoryModel::find($book->cat_id);
        $chapter_list = BookChapterModel::where('book_id', $id)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->all();
        return $this->show(compact('book', 'cat', 'chapter_list'));
    }

    public function readAction(int $id) {
        $chapter = BookChapterModel::find($id);
        BookRepository::clickLog()->add(BookRepository::LOG_TYPE_BOOK, $chapter->book_id, 0);
        $book = BookModel::find($chapter->book_id);
        if (auth()->guest() && $book->classify > 0) {
            return $this->redirectWithAuth();
        }
        $cat = BookCategoryModel::find($book->cat_id);
        HistoryRepository::log($chapter);
        $setting = new Setting();
        $setting->load()->save();
        return $this->show(compact('book', 'cat', 'chapter', 'like_book', 'new_book', 'setting'));
    }

    public function txtAction(int $id, Output $output) {
        try {
            return $output->file(DownloadRepository::txt($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }

    public function zipAction(int $id, Output $output) {
        try {
            return $output->file(DownloadRepository::zip($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.zip');
            return $output->custom($ex->getMessage(), 'zip');
        }
    }
}