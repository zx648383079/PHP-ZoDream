<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookModel;


class HomeController extends Controller {

    public function indexAction() {
        $recommend_book = BookModel::ofClassify()->isOpen()->limit(4)->get();
        $hot_book = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();

        $click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(15)->all();
        $book_list = BookModel::ofClassify()->isOpen()->orderBy('updated_at', 'desc')->limit(20)->all();
        return $this->show(compact('recommend_book',
            'hot_book', 'book_list', 'click_bang',));
    }
}