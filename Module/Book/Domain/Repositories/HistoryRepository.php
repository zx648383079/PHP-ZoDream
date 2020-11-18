<?php
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Zodream\Html\Page;
use Zodream\Infrastructure\Cookie;

class HistoryRepository {


    public static function hasBook($id) {
        if (auth()->guest()) {
            return false;
        }
        return BookHistoryModel::where('user_id', auth()->id())
            ->where('book_id', $id)->count() > 0;
    }

    public static function log(BookChapterModel $chapter) {
        if (!auth()->guest()) {
            static::record($chapter->book_id, $chapter->id);
            return;
        }
        $history = app('request')->cookie(BookHistoryModel::tableName());
        $history = empty($history) ? [] : unserialize($history);
        $history[$chapter->book_id] = $chapter->id;
        if (count($history) > 10) {
            $history = array_splice($history, 0, 10);
        }
        Cookie::forever(BookHistoryModel::tableName(), serialize($history));
    }

    public static function record($book, $chapter, $progress = 0) {
        if (auth()->guest()) {
            return;
        }
        if (static::hasHistory($book)) {
            BookHistoryModel::where('book_id', $book)
                ->where('user_id', auth()->id())->update([
                    'chapter_id' => $chapter,
                    'progress' => $progress
                ]);
            return;
        }
        BookHistoryModel::create([
            'chapter_id' => $chapter,
            'progress' => $progress,
            'user_id' => auth()->id(),
            'book_id' => $book
        ]);
    }

    public static function removeBook($id) {
        if (auth()->guest()) {
            return;
        }
        BookHistoryModel::where('user_id', auth()->id())
            ->where('book_id', $id)->delete();
    }

    public static function getHistoryId() {
        $history = app('request')->cookie(BookHistoryModel::tableName());
        return empty($history) ? [] : unserialize($history);
    }

    public static function hasHistory($book_id) {
        return BookHistoryModel::where('book_id', $book_id)
                ->where('user_id', auth()->id())->count() > 0;
    }

    /**
     * 获取一页的章节内容
     * @return Page
     * @throws \Exception
     */
    public static function getHistory() {
        if (!auth()->guest()) {
            return BookHistoryModel::with('book', 'chapter')
                ->where('user_id', auth()->id())->page();
        }
        $items = static::getHistoryId();
        if (empty($items)) {
            return new Page(0);
        }
        $page = BookChapterModel::with('book')->whereIn('id' ,$items)->page();
        $items = [];
        foreach ($page as $item) {
            $arg = new BookHistoryModel();
            $arg->setRelation('book', $item->book);
            $arg->setRelation('chapter', $item);
            $arg->progress = 0;
            $items[] = $arg;
        }
        return $page->setPage($items);
    }
}