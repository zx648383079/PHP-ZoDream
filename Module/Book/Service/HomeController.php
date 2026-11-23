<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookModel;


class HomeController extends Controller {

    public function indexAction() {
        $recommend_book = BookModel::ofClassify()->isOpen()->take(4)->get();
        $hot_book = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();

        $click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(15)->get();
        $book_list = BookModel::ofClassify()->isOpen()->orderBy('updated_at', SORT_DESC)->take(20)->get();
        return $this->show(compact('recommend_book',
            'hot_book', 'book_list', 'click_bang',));
    }
}