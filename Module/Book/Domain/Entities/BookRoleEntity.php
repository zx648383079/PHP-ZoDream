<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $book_id
 * @property string $name
 * @property string $avatar
 * @property string $description
 * @property string $identity
 * @property string $properties
 */
class BookRoleEntity extends Entity {
    public static function tableName(): string {
        return 'book_role';
    }

	protected function rules(): array {
		return [
            'book_id' => 'required|int',
            'name' => 'required|string:0,50',
            'avatar' => 'string:0,200',
            'description' => 'string:0,200',
            'identity' => 'string:0,20',
            'properties' => 'string:0,200',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'description' => 'Description',
            'identity' => 'Identity',
            'properties' => 'Properties',
        ];
	}
}