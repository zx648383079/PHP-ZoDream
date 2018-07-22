<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Domain\Access\Auth;
use Zodream\Html\Page;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Http\Request;

/**
 * Class BookHistoryModel
 * @package Module\Book\Domain\Model
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookHistoryModel extends Model {
    public static function tableName() {
        return 'book_history';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'book_id' => 'required|int',
            'chapter_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'book_id' => 'Book Id',
            'chapter_id' => 'Chapter Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public static function log(BookChapterModel $chapter) {
        if (!Auth::guest()) {
            if (static::hasHistory($chapter->book_id)) {
                static::where('book_id', $chapter->book_id)
                    ->where('user_id', Auth::id())->update([
                        'chapter_id' => $chapter->id
                    ]);
                return;
            }
            static::create([
                'chapter_id' => $chapter->id,
                'user_id' => Auth::id(),
                'book_id' => $chapter->book_id
            ]);
            return;
        }
        $history = app('request')->cookie(static::tableName());
        $history = empty($history) ? [] : unserialize($history);
        $history[$chapter->book_id] = $chapter->id;
        if (count($history) > 10) {
            $history = array_splice($history, 0, 10);
        }
        Cookie::forever(static::tableName(), serialize($history));
    }

    public static function getHistoryId() {
        $history = app('request')->cookie(static::tableName());
        return empty($history) ? [] : unserialize($history);
    }

    public static function hasHistory($book_id) {
        return static::where('book_id', $book_id)
                ->where('user_id', Auth::id())->count() > 0;
    }

    /**
     * 获取一页的章节内容
     * @return Page
     */
    public static function getHistory() {
        if (Auth::guest()) {
            return BookChapterModel::with('book')
                ->whereIn('id', static::getHistoryId())->page();
        }
        $page = static::where('user_id', Auth::id())->orderBy('updated_at', 'desc')
            ->select('chapter_id')
            ->page();
        $ids = [];
        foreach ($page as $item) {
            $ids[] = $item->chapter_id;
        }
        $page->setPage(BookChapterModel::with('book')
            ->whereIn('id', $ids)->all());
        return $page;
    }
}