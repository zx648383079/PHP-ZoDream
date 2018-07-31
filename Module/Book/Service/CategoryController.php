<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;

class CategoryController extends Controller {

    public function indexAction($id) {
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::ofClassify()->where('cat_id', $id)->order('click_count', 'desc')->limit(6)->all();
        $book = BookModel::ofClassify()->where('cat_id', $id)->order('created_at', 'desc')->one();
        $cat_book = BookModel::ofClassify()->where('cat_id', $id)->limit(4)->all();

        $click_bang = BookModel::ofClassify()->where('cat_id', $id)->order('click_count', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::ofClassify()->where('cat_id', $id)->order('size', 'desc')->limit(10)->all();


        $book_list = BookModel::ofClassify()->where('cat_id', $id)->order('updated_at', 'desc')->limit(20)->all();


        $new_book = BookModel::ofClassify()->where('cat_id', $id)->where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::ofClassify()->where('cat_id', $id)->where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];
        return $this->show(compact('book_list', 'cat', 'cat_book', 'book',  'new_book', 'hot_book', 'over_book', 'click_bang', 'size_bang', 'recommend_bang', 'hot_author'));
    }
}