<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property integer $book_count
 * @property integer $click_count
 * @property integer $collect_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class ListEntity extends Entity {
    public static function tableName(): string {
        return 'book_list';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'title' => 'required|string:0,50',
            'description' => 'string:0,200',
            'book_count' => 'int:0,99999',
            'click_count' => 'int:0,99999',
            'collect_count' => 'int:0,99999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'title' => 'Title',
            'description' => 'Description',
            'book_count' => 'Book Count',
            'click_count' => 'Click Count',
            'collect_count' => 'Collect Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}