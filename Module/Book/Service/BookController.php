<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookClickLogModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\HistoryRepository;
use Module\Book\Domain\Setting;
use Zodream\Disk\File;
use Zodream\Disk\Stream;

use Zodream\Service\Factory;

class BookController extends Controller {

    protected function rules() {
        return [
            'txt' => '@',
            'zip' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($id) {
        if (app('request')->isMobile()) {
            return $this->redirect(['./mobile/book', 'id' => $id]);
        }
        $book = BookModel::find($id);
        if (auth()->guest() && $book->classify > 0) {
            return $this->redirectWithAuth();
        }
        $cat = BookCategoryModel::find($book->cat_id);
        $chapter_list = BookChapterModel::where('book_id', $id)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->all();
        $hot_book = BookModel::ofClassify()->where('id', '<>', $book->id)->orderBy('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::ofClassify()->where('cat_id', $book->cat_id)->where('id', '<>', $id)->orderBy('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::ofClassify()->where('author_id', $book->author_id)->orderBy('created_at', 'desc')->all();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }

    public function readAction($id) {
        if (app('request')->isMobile()) {
            return $this->redirect(['./mobile/book/read', 'id' => $id]);
        }
        $chapter = BookChapterModel::find($id);
        BookClickLogModel::logBook($chapter->book_id);
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

    public function downloadAction($id) {
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

    public function txtAction($id) {
        $book = BookModel::find($id);
        $file = $this->getBookFile($book);
        return Factory::response()->file($file);
    }

    public function zipAction($id) {
        $book = BookModel::find($id);
        $file = $this->getBookFile($book);
        $zipFile = new File($file->getFullName().'.zip');
        $zip = new \ZipArchive();
        $zip->open((string)$zipFile,\ZipArchive::CREATE);
        $zip->addFile((string)$file, 'all.txt');
        $zip->close();
        $zipFile->setName($book->name.'.zip');
        return Factory::response()->file($zipFile);
    }

    protected function getBookFile(BookModel $book) {
        $file = Factory::root()->file('data/cache/book_'. $book->id);
        if (!$file->exist() || $file->modifyTime() < time() - 3600) {
            $stream = new Stream($file);
            $stream->open('w');

            $chapter_list = BookChapterModel::where('book_id', $book->id)
                ->orderBy('id', 'asc')
                ->all();
            foreach ($chapter_list as $item) {
                $stream->writeLine($item->title);
                $stream->writeLine('');
                $content = BookChapterBodyModel::where('id', $item->id)->value('content');
                $stream->writeLine($content);
                $stream->writeLines([
                    '',
                    '',
                ]);
            }
            $stream->close();
        }
        $file->setName($book->name.'.txt');
        return $file;
    }
}