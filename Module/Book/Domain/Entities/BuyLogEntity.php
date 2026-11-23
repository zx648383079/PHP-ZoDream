<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $user_id
 * @property integer $created_at
 */
class BuyLogEntity extends Entity {
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