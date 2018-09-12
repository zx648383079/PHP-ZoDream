<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Service\Controller;

class CategoryController extends Controller {

    public function indexAction($id) {
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::ofClassify()->where('cat_id', $id)->orderBy('click_count', 'desc')->limit(6)->all();
        $new_book = BookModel::ofClassify()->where('cat_id', $id)->orderBy('created_at', 'desc')->limit(4)->all();
        $over_book = BookModel::ofClassify()->where('cat_id', $id)->where('over_at > 0')->limit(4)->all();

        $click_bang = BookModel::ofClassify()->where('cat_id', $id)->orderBy('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::ofClassify()->where('cat_id', $id)->orderBy('size', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::ofClassify()->where('cat_id', $id)->orderBy('size', 'desc')->limit(10)->all();
        $book_list = BookModel::ofClassify()->where('cat_id', $id)->orderBy('updated_at', 'desc')->limit(20)->all();
        return $this->show(compact('book_list', 'cat', 'new_book', 'hot_book', 'over_book', 'click_bang', 'size_bang', 'recommend_bang'));
    }
}