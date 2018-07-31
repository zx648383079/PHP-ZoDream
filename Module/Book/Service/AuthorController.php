<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;

class AuthorController extends Controller {

    public function indexAction($id) {
        $author = BookAuthorModel::find($id);
        $hot_book = BookModel::ofClassify()->where('author_id',  $id)->order('click_count', 'desc')->limit(15)->all();
        $book_list = BookModel::ofClassify()->where('author_id',  $id)->all();
        $month_click = BookModel::ofClassify()->where('author_id',  $id)->order('click_count', 'desc')->limit(5)->all();
        $hot_author = BookAuthorModel::limit(10)->all();
        return $this->show(compact('author', 'hot_book', 'book_list', 'month_click', 'hot_author'));
    }
}