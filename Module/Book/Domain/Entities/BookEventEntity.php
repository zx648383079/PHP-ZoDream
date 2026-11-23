<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class BookEventModel
 * @property integer $id
 * @property integer $book_id
 * @property string $name
 * @property string $location
 * @property string $role
 * @property string $time_node
 * @property integer $updated_at
 * @property integer $created_at
 */

class BookEventEntity extends Entity {
    public static function tableName(): string {
        return 'book_event';
    }

	protected function rules(): array {
		return [
            'book_id' => 'required|int',
            'name' => 'required|string:0,200',
            'location' => 'string:0,200',
            'role' => 'string:0,200',
            'time_node' => '',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'name' => 'Name',
            'location' => 'Location',
            'role' => 'Role',
            'time_node' => 'Time Node',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}