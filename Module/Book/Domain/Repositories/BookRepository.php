<?php
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookLogModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\BookPageModel;

class BookRepository {

    public static function getList($id = null,
                                   $category = null, $keywords = null, $top = null, $over = false,
                                   $page = 1, $per_page = 20) {
        $query = BookPageModel::with('category', 'author')->ofClassify()
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })
            ->when(is_array($id), function ($query) use ($id) {
                $query->whereIn('id', $id);
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            });
        return !empty($top) ?
            BookLogModel::getPage($query, $top, $page, $per_page)
            : $query->page($per_page);
    }
}