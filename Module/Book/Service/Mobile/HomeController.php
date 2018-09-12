<?php
namespace Module\Book\Service\Mobile;

use Module\Book\Domain\Model\BookModel;
use Module\Book\Service\Controller;

class HomeController extends Controller {

    public function indexAction() {
        $recommend_book = BookModel::ofClassify()->limit(4)->all();
        $hot_book = BookModel::ofClassify()->orderBy('click_count', 'desc')->limit(10)->all();
        $new_book = BookModel::ofClassify()->orderBy('created_at', 'desc')->limit(7)->all();
        $over_book = BookModel::ofClassify()->where('over_at > 0')->limit(7)->all();
        $click_bang = $hot_book;
        $recommend_bang = BookModel::ofClassify()->orderBy('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::ofClassify()->orderBy('size', 'desc')->limit(10)->all();
        $update_book = BookModel::ofClassify()->orderBy('updated_at', 'desc')->limit(20)->all();
        return $this->show(compact('recommend_book', 'hot_book', 'new_book', 'over_book', 'click_bang', 'recommend_bang', 'size_bang', 'update_book'));
    }
}