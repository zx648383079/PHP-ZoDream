<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;

class CategoryController extends Controller {

    public function indexAction(int $id) {
        $cat = BookCategoryModel::find($id);
        $hot_book = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('click_count', SORT_DESC)->take(6)->get();

        $click_bang = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('click_count', SORT_DESC)->take(10)->get();


        $book_list = BookModel::ofClassify()->isOpen()->where('cat_id', $id)->orderBy('updated_at', SORT_DESC)->take(20)->get();

        return $this->show(compact('book_list', 'cat', 'hot_book','click_bang'));
    }
}