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
}