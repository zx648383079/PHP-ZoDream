<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\ModuleController;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;

class HomeController extends ModuleController {

    public function indexAction() {
        $recommend_book = BookModel::limit(4)->all();
        $hot_book = BookModel::order('click_count', 'desc')->limit(10)->all();

        $new_recommend_book = BookModel::where('size', '<', 50000)->order('created_at', 'desc')->order('recommend_count', 'desc')->limit(12)->all();
        $week_click_book = BookModel::order('click_count', 'desc')->limit(5)->all();
        $week_recommend_book = BookModel::order('recommend_count', 'desc')->limit(5)->all();
        $best_recommend_book = BookModel::order('recommend_count', 'desc')->limit(12)->all();

        $month_click_book = BookModel::order('click_count', 'desc')->limit(5)->all();
        $month_recommend_book = BookModel::order('recommend_count', 'desc')->limit(5)->all();

        $click_bang = BookModel::order('click_count', 'desc')->limit(15)->all();
        $recommend_bang = BookModel::order('click_count', 'desc')->limit(15)->all();
        $size_bang = BookModel::order('size', 'desc')->limit(15)->all();
        $this->getShare();

        $book_list = BookModel::order('updated_at', 'desc')->limit(20)->all();
        $new_book = BookModel::where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];
        $book = BookModel::one();
        return $this->show(compact('recommend_book', 'book',
            'hot_book', 'book_list', 'new_book', 'over_book', 'click_bang', 'recommend_bang',
            'size_bang', 'hot_author',
            'new_recommend_book', 'week_click_book', 'week_recommend_book', 'best_recommend_book', 'month_click_book', 'month_recommend_book'));
    }

    public function searchAction($keywords) {
        $book_list = BookModel::when(!empty($keywords), function ($query) {
            BookModel::search($query, ['name']);
        })->page();
        $this->getShare();
        return $this->show(compact('book_list', 'keywords'));
    }

    public function topAction() {
        $click_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $month_click_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $week_click_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $month_recommend_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $week_recommend_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::order('size', 'desc')->limit(10)->all();

        $new_book = BookModel::where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];

        $this->getShare();
        return $this->show(compact('click_bang', 'month_click_bang', 'week_click_bang', 'recommend_bang', 'month_recommend_bang', 'week_recommend_bang', 'size_bang', 'new_book', 'over_book', 'hot_author'));
    }

    public function categoryAction($id) {
        $this->getShare();
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::where('cat_id', $id)->order('click_count', 'desc')->limit(6)->all();
        $book = BookModel::where('cat_id', $id)->order('created_at', 'desc')->one();
        $cat_book = BookModel::where('cat_id', $id)->limit(4)->all();

        $click_bang = BookModel::where('cat_id', $id)->order('click_count', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::where('cat_id', $id)->order('size', 'desc')->limit(10)->all();


        $book_list = BookModel::where('cat_id', $id)->order('updated_at', 'desc')->limit(20)->all();


        $new_book = BookModel::where('cat_id', $id)->where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::where('cat_id', $id)->where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];

        return $this->show(compact('book_list', 'cat', 'cat_book', 'book',  'new_book', 'hot_book', 'over_book', 'click_bang', 'size_bang', 'recommend_bang', 'hot_author'));
    }

    public function listAction($cat_id = 0, $sort = 'updated_at', $status = 0) {
        $this->getShare();
        $book_list = BookModel::when($status == 1, function ($query) {
            $query->where('over_at', 0);
        })->when($status == 2, function ($query) {
            $query->where('over_at > 0');
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->when($sort == 'updated_at', function ($query) {
            $query->order('updated_at', 'desc');
        })->when($sort == 'created_at', function ($query) {
            $query->order('created_at', 'desc');
        })->when($sort == 'click_count', function ($query) {
            $query->order('click_count', 'desc');
        })->when($sort == 'month_click', function ($query) {
            $query->order('click_count', 'desc');
        })->when($sort == 'size', function ($query) {
            $query->order('size', 'desc');
        })->page();
        $sort_list = [
            'updated_at' => '最后更新',
            'click_count' => '总人气',
            'month_click' => '月人气',
            'created_at' => '新书',
            'size' => '字数'
        ];
        $new_book = BookModel::where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];
        return $this->show(compact('book_list', 'cat_id', 'sort', 'status', 'sort_list', 'new_book', 'over_book', 'hot_author'));
    }

    public function logAction() {
        $this->getShare();
        $book_list = [];
        return $this->show(compact('book_list'));
    }

    public function chapterAction($id) {
        $book = BookModel::find($id);
        $cat = BookCategoryModel::find($book->cat_id);
        $chapter_list = BookChapterModel::where('book_id', $id)->order('created_at', 'asc')->all();
        $hot_book = BookModel::where('id', '<>', $book->id)->order('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $id)->order('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::where('author_id', $book->author_id)->order('created_at', 'desc')->all();
        $this->getShare();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }

    public function authorAction($id) {
        $author = BookAuthorModel::find($id);
        $hot_book = BookModel::where('author_id',  $id)->order('click_count', 'desc')->limit(15)->all();
        $book_list = BookModel::where('author_id',  $id)->all();
        $month_click = BookModel::where('author_id',  $id)->order('click_count', 'desc')->limit(5)->all();
        $hot_author = BookAuthorModel::limit(10)->all();
        $this->getShare();
        return $this->show(compact('author', 'hot_book', 'book_list', 'month_click', 'hot_author'));
    }

    public function download($id) {
        $book = BookModel::find($id);
        $cat = BookCategoryModel::find($book->cat_id);
        $hot_book = BookModel::where('id', '<>', $book->id)->order('click_count', 'desc')->limit(15)->all();
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $id)->order('click_count', 'desc')->limit(10)->all();
        $author_book = BookModel::where('author_id', $book->author_id)->order('created_at', 'desc')->all();
        $this->getShare();
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book', 'hot_book', 'author_book'));
    }

    public function detailAction($id) {
        $chapter = BookChapterModel::find($id);
        $book = BookModel::find($chapter->book_id);
        $cat = BookCategoryModel::find($book->cat_id);
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->order('click_count', 'desc')->limit(8)->all();
        $new_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->where('size < 50000')->order('click_count', 'desc')->limit(8)->all();
        return $this->show(compact('book', 'cat', 'chapter', 'like_book', 'new_book'));
    }

    protected function getShare() {
        $cat_list = BookCategoryModel::all();

        $this->send(compact('cat_list'));
    }
}