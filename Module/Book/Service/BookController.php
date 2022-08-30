<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookClickLogModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\BookRepository;
use Module\Book\Domain\Repositories\DownloadRepository;
use Module\Book\Domain\Repositories\HistoryRepository;
use Module\Book\Domain\Setting;
use Zodream\Disk\File;
use Zodream\Disk\Stream;

use Zodream\Infrastructure\Contracts\Http\Output;


class BookController extends Controller {

    public function rules() {
        return [
            'txt' => '@',
            'zip' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id) {
        if (request()->isMobile()) {
            return $this->redirect(['./mobile/book', 'id' => $id]);
        }
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
        $hot_book = BookModel::ofClassify()->isOpen()->where('id', '<>', $book->id)->orderBy('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::ofClassify()->isOpen()->where('cat_id', $book->cat_id)->where('id', '<>', $id)->orderBy('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::ofClassify()->isOpen()->where('author_id', $book->author_id)->orderBy('created_at', 'desc')->all();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }

    public function readAction(int $id) {
        if (request()->isMobile()) {
            return $this->redirect(['./mobile/book/read', 'id' => $id]);
        }
        $chapter = BookChapterModel::find($id);
        BookRepository::clickLog()->add(BookRepository::LOG_TYPE_BOOK, $chapter->book_id, 0);
        $book = BookModel::find($chapter->book_id);
        if (auth()->guest() && $book->classify > 0) {
            return $this->redirectWithAuth();
        }
        $cat = BookCategoryModel::find($book->cat_id);
        $like_book = BookModel::ofClassify()->where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->orderBy('click_count', 'desc')->limit(8)->all();
        $new_book = BookModel::ofClassify()->where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->where('size < 50000')->orderBy('click_count', 'desc')->limit(8)->all();
        HistoryRepository::log($chapter);
        $setting = new Setting();
        $setting->load()->save();
        return $this->show(compact('book', 'cat', 'chapter', 'like_book', 'new_book', 'setting'));
    }

    public function downloadAction(int $id) {
        $book = BookModel::find($id);
        if (auth()->guest() && $book->classify > 0) {
            return $this->redirectWithAuth();
        }
        $cat = BookCategoryModel::find($book->cat_id);
        $hot_book = BookModel::ofClassify()->where('id', '<>', $book->id)->orderBy('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::ofClassify()->where('cat_id', $book->cat_id)->where('id', '<>', $id)->orderBy('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::ofClassify()->where('author_id', $book->author_id)->orderBy('created_at', 'desc')->all();
        return $this->show(compact('book', 'cat',
            'like_book', 'hot_book', 'author_book'));
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