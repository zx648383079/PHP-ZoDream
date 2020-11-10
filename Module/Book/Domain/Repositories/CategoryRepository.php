<?php
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;

class CategoryRepository {

    public static function all() {
        return BookCategoryModel::orderBy('id', 'asc')->get();
    }

    public static function getList() {
        $items = self::all();
        foreach ($items as $item) {
            $thumb = BookModel::where('cat_id', $item->id)->value('cover');
            if (empty($thumb)) {
                $thumb = BookRepository::DEFAULT_COVER;
            }
            $item['thumb'] = url()->asset($thumb);
            $item['book_count'] = BookModel::where('cat_id', $item->id)->count();
        }
        return $items;
    }
}