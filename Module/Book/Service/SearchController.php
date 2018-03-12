<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;

class SearchController extends Controller {

    public function indexAction($keywords) {
        $book_list = BookModel::when(!empty($keywords), function ($query) {
            BookModel::search($query, ['name']);
        })->page();
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

        return $this->show(compact('click_bang', 'month_click_bang', 'week_click_bang', 'recommend_bang', 'month_recommend_bang', 'week_recommend_bang', 'size_bang', 'new_book', 'over_book', 'hot_author'));
    }

    public function listAction($cat_id = 0, $sort = 'updated_at', $status = 0) {
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
        $hot_author = BookAuthorModel::limit(10)->all();
        return $this->show(compact('book_list', 'cat_id', 'sort', 'status', 'sort_list', 'new_book', 'over_book', 'hot_author'));
    }

    public function downloadAction($cat_id = 0, $status = 0) {
        $book_list = BookModel::when($status == 1, function ($query) {
            $query->where('over_at', 0);
        })->when($status == 2, function ($query) {
            $query->where('over_at > 0');
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->page();
        $new_book = BookModel::where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = BookAuthorModel::limit(10)->all();
        return $this->show(compact('book_list', 'cat_id', 'sort', 'status', 'sort_list', 'new_book', 'over_book', 'hot_author'));
    }
}