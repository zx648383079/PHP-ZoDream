<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Module\ModuleController;

class WapController extends ModuleController {

    public function indexAction() {
        $recommend_book = BookModel::limit(4)->all();
        $hot_book = BookModel::order('click_count', 'desc')->limit(10)->all();
        $new_book = BookModel::order('created_at', 'desc')->limit(7)->all();
        $over_book = BookModel::where('over_at > 0')->limit(7)->all();
        $click_bang = $hot_book;
        $recommend_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::order('size', 'desc')->limit(10)->all();
        $update_book = BookModel::order('updated_at', 'desc')->limit(20)->all();
        $this->getShare();
        return $this->show(compact('recommend_book', 'hot_book', 'new_book', 'over_book', 'click_bang', 'recommend_bang', 'size_bang', 'update_book'));
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
        $recommend_bang = BookModel::order('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::order('size', 'desc')->limit(10)->all();
        $this->getShare();
        return $this->show(compact('click_bang', 'recommend_bang', 'size_bang'));
    }

    public function categoryAction($id) {
        $this->getShare();
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::where('cat_id', $id)->order('click_count', 'desc')->limit(6)->all();
        $new_book = BookModel::where('cat_id', $id)->order('created_at', 'desc')->limit(4)->all();
        $over_book = BookModel::where('cat_id', $id)->where('over_at > 0')->limit(4)->all();

        $click_bang = BookModel::where('cat_id', $id)->order('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::where('cat_id', $id)->order('size', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::where('cat_id', $id)->order('size', 'desc')->limit(10)->all();
        $book_list = BookModel::where('cat_id', $id)->order('updated_at', 'desc')->limit(20)->all();
        return $this->show(compact('book_list', 'cat', 'new_book', 'hot_book', 'over_book', 'click_bang', 'size_bang', 'recommend_bang'));
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
        return $this->show(compact('book_list', 'cat_id', 'sort', 'status', 'sort_list'));
    }

    public function logAction() {
        $this->getShare();
        $book_list = [];
        return $this->show(compact('book_list'));
    }

    public function chapterAction($id, $page = null) {
        $book = BookModel::find($id);
        $cat = BookCategoryModel::find($book->cat_id);
        $chapter_list = BookChapterModel::where('book_id', $id)->order('created_at', 'asc')->page();
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $id)->order('click_count', 'desc')->limit(8)->all();
        $this->getShare();
        if (is_null($page)) {
            $new_chapter = BookChapterModel::where('book_id', $id)->order('created_at', 'desc')->limit(3)->all();
            $this->send(compact('new_chapter'));
        }
        return $this->show(compact('book', 'cat', 'chapter_list', 'like_book'));
    }

    public function authorAction($id) {
        $author = BookAuthorModel::find($id);
        $book_list = BookModel::where('author_id',  $id)->all();
        $this->getShare();
        return $this->show(compact('author', 'hot_book', 'book_list', 'month_click', 'hot_author'));
    }

    public function detailAction($id) {
        $chapter = BookChapterModel::find($id);
        $book = BookModel::find($chapter->book_id);
        $cat = BookCategoryModel::find($book->cat_id);
        $like_book = BookModel::where('cat_id', $book->cat_id)->where('id', '<>', $book->id)->order('click_count', 'desc')->limit(8)->all();
        return $this->show(compact('book', 'cat', 'chapter', 'like_book'));
    }

    protected function getShare() {
        $cat_list = BookCategoryModel::all();
        $site_name = 'ZoDream 读书';
        $this->send(compact('cat_list', 'site_name'));
    }


}