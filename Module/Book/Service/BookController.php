<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Setting;

class BookController extends Controller {

    public function indexAction($id) {
        $book = BookModel::find($id);
        $cat = BookCategoryModel::find($book->cat_id);
        $chapter_list = BookChapterModel::where('book_id', $id)->order('created_at', 'asc')->all();
        $hot_book = BookModel::where('id', '<>', $book->id)->order('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $id)->order('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::where('author_id', $book->author_id)->order('created_at', 'desc')->all();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }

    public function readAction($id) {
        $chapter = BookChapterModel::find($id);
        $book = BookModel::find($chapter->book_id);
        $cat = BookCategoryModel::find($book->cat_id);
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->order('click_count', 'desc')->limit(8)->all();
        $new_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->where('size < 50000')->order('click_count', 'desc')->limit(8)->all();
        BookHistoryModel::log($chapter);
        $setting = new Setting();
        $setting->load()->apply()->save();
        return $this->show(compact('book', 'cat', 'chapter', 'like_book', 'new_book', 'setting'));
    }

    public function downloadAction($id) {
        $book = BookModel::find($id);
        $cat = BookCategoryModel::find($book->cat_id);
        $hot_book = BookModel::where('id', '<>', $book->id)->order('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $id)->order('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::where('author_id', $book->author_id)->order('created_at', 'desc')->all();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }
}