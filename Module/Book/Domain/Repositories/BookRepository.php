<?php
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookClickLogModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\BookPageModel;

class BookRepository {

    const DEFAULT_COVER = '/assets/images/book_default.jpg';

    public static function getList($id = null,
                                   $category = null,
                                   $keywords = null,
                                   $top = null,
                                   $status = 0,
                                   $author = 0,
                                   $page = 1, $per_page = 20) {
        $query = BookPageModel::with('category', 'author')->ofClassify()
            ->when(!empty($keywords), function ($query) {
                BookModel::searchWhere($query, 'name');
            })
            ->when(is_array($id), function ($query) use ($id) {
                $query->whereIn('id', $id);
            })
            ->when($author > 0, function ($query) use ($author) {
                $query->where('author_id', intval($author));
            })
            ->when($status == 1, function ($query) {
                $query->where('over_at', 0);
            })->when($status == 2, function ($query) {
                $query->where('over_at > 0');
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            });
        return !empty($top) ?
            BookClickLogModel::getPage($query, $top, $page, $per_page)
            : $query->page($per_page);
    }

    public static function chapters($book) {
        return BookChapterModel::where('book_id', $book)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->all();
    }
}