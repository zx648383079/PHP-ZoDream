<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $progress
 * @property integer $created_at
 * @property integer $updated_at
 */
class HistoryEntity extends Entity {
    public static function tableName(): string {
        return 'book_history';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'book_id' => 'required|int',
            'chapter_id' => 'int',
            'progress' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
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
}