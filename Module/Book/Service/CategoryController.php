<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;

class CategoryController extends Controller {

    public function indexAction(int $id) {
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('click_count', 'desc')->limit(6)->all();

        $click_bang = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('click_count', 'desc')->limit(10)->all();


        $book_list = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('updated_at', 'desc')->limit(20)->all();

        return $this->show(compact('book_list', 'cat', 'hot_book','click_bang'));
    }
}