<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $target_id
 * @property string $name
 * @property string $content
 */
class BookMetaEntity extends Entity {
    public static function tableName(): string {
        return 'book_meta';
    }

	protected function rules(): array {
		return [
            'target_id' => 'required|int',
            'name' => 'required|string:0,50',
            'content' => 'string:0,255',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'target_id' => 'Target Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
	}
}