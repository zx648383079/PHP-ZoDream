<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\BookModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $category_count = 0;
        $book_today = BookModel::where('created_at', '>=' ,$todayStart)->count();
        $book_count = BookModel::query()->count();
        $chapter_today = BookChapterModel::where('created_at', '>=' ,$todayStart)->count();
        $chapter_count = BookChapterModel::query()->count();
        $word_today = BookChapterModel::where('created_at', '>=' ,$todayStart)->sum('size');
        $word_count = BookChapterModel::query()->sum('size');
        $author_count = BookAuthorModel::query()->count();
        $list_today = BookListModel::where('created_at', '>=' ,$todayStart)->count();
        $list_count = BookListModel::query()->count();
        $view_today = BookRepository::clickLog()->todayCount(BookRepository::LOG_TYPE_BOOK, 0, BookRepository::LOG_ACTION_CLICK);
        $view_count = BookModel::query()->sum('click_count');
        return compact('category_count',  'book_count', 'book_today',
        'chapter_count', 'chapter_today', 'word_count', 'word_today', 'author_count',
        'list_count', 'list_today', 'view_count', 'view_today');
    }
}