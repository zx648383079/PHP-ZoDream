<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Service\Controller;

class SearchController extends Controller {

    public function indexAction(string $keywords = '') {
        $book_list = BookModel::ofClassify()->isOpen()->when(!empty($keywords), function ($query) {
            BookModel::searchWhere($query, ['name']);
        })->page();
        return $this->show(compact('book_list', 'keywords'));
    }

    public function topAction() {
        $click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::ofClassify()->isOpen()->orderBy('size', 'desc')->limit(10)->all();
        return $this->show(compact('click_bang', 'recommend_bang', 'size_bang'));
    }

    public function listAction($cat_id = 0, $sort = 'updated_at', $status = 0) {
        $book_list = BookModel::ofClassify()->isOpen()->when($status == 1, function ($query) {
            $query->where('over_at', 0);
        })->when($status == 2, function ($query) {
            $query->where('over_at > 0');
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->when($sort == 'updated_at', function ($query) {
            $query->orderBy('updated_at', 'desc');
        })->when($sort == 'created_at', function ($query) {
            $query->orderBy('created_at', 'desc');
        })->when($sort == 'click_count', function ($query) {
            $query->orderBy('click_count', 'desc');
        })->when($sort == 'month_click', function ($query) {
            $query->orderBy('click_count', 'desc');
        })->when($sort == 'size', function ($query) {
            $query->orderBy('size', 'desc');
        })->page();
        $sort_list = [
            'updated_at' => '最后更新',
            'click_count' => '总人气',
            'month_click' => '月人气',
            'created_at' => '新书',
            'size' => '字数'
        ];
        $cat = BookCategoryModel::findOrNew($cat_id);
        return $this->show(compact('book_list', 'cat_id', 'sort', 'status', 'sort_list', 'cat'));
    }

    public function downloadAction($cat_id = 0, $status = 0) {
        $book_list = BookModel::ofClassify()->isOpen()->when($status == 1, function ($query) {
            $query->where('over_at', 0);
        })->when($status == 2, function ($query) {
            $query->where('over_at > 0');
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->page();
        $new_book = BookModel::ofClassify()->isOpen()->where('size < 50000')->orderBy('created_at', 'desc')->orderBy('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::ofClassify()->isOpen()->where('over_at > 0')->orderBy('click_count', 'desc')->limit(10)->all();
        $hot_author = BookAuthorModel::limit(10)->all();
        return $this->show(compact('book_list', 'cat_id', 'status', 'new_book', 'over_book', 'hot_author'));
    }
}