<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Html\Page;
use Zodream\Infrastructure\Cookie;


/**
 * Class BookHistoryModel
 * @package Module\Book\Domain\Model
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $progress
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookHistoryModel extends Model {

    protected $append = ['book', 'chapter'];

    public static function tableName() {
        return 'book_history';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'book_id' => 'required|int',
            'chapter_id' => 'int',
            'progress' => 'int',
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
            'progress' => 'Progress',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function book() {
        return $this->hasOne(BookSimpleModel::class, 'id', 'book_id');
    }

    public function chapter() {
        return $this->hasOne(ChapterSimpleModel::class,
            'id', 'chapter_id');
    }

    public static function log(BookChapterModel $chapter) {
        if (!auth()->guest()) {
            static::record($chapter->book_id, $chapter->id);
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

    public static function record($book, $chapter, $progress = 0) {
        if (auth()->guest()) {
            return;
        }
        if (static::hasHistory($book)) {
            static::where('book_id', $book)
                ->where('user_id', auth()->id())->update([
                    'chapter_id' => $chapter,
                    'progress' => $progress
                ]);
            return;
        }
        static::create([
            'chapter_id' => $chapter,
            'progress' => $progress,
            'user_id' => auth()->id(),
            'book_id' => $book
        ]);
    }

    public static function getHistoryId() {
        $history = app('request')->cookie(static::tableName());
        return empty($history) ? [] : unserialize($history);
    }

    public static function hasHistory($book_id) {
        return static::where('book_id', $book_id)
                ->where('user_id', auth()->id())->count() > 0;
    }

    /**
     * 获取一页的章节内容
     * @return Page
     * @throws \Exception
     */
    public static function getHistory() {
        if (!auth()->guest()) {
            return static::with('book', 'chapter')
                ->where('user_id', auth()->id())->page();
        }
        $items = static::getHistoryId();
        if (empty($items)) {
            return new Page(0);
        }
        $page = BookChapterModel::with('book')->whereIn('id' ,$items)->page();
        $items = [];
        foreach ($page as $item) {
            $arg = new static();
            $arg->setRelation('book', $item->book);
            $arg->setRelation('chapter', $item);
            $arg->progress = 0;
            $items[] = $arg;
        }
        return $page->setPage($items);
    }
}