<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookBuyLogModel
 * @package Module\Book\Domain\Model
 * @property integer $id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $user_id
 * @property integer $created_at
 */
class BookBuyLogModel extends Model {

    public static function tableName(): string {
        return 'book_buy_log';
    }

    protected function rules(): array {
        return [
            'book_id' => 'required|int',
            'chapter_id' => 'required|int',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'chapter_id' => 'Chapter Id',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }
}