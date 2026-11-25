<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $target_id
 * @property integer $type
 * @property integer $link_id
 * @property string $name
 * @property string $avatar
 * @property string $description
 * @property string $keywords
 * @property string $content
 * @property string $properties
 * @property string $start_at
 * @property string $end_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class BookMetaEntity extends Entity {
    public static function tableName(): string {
        return 'book_meta';
    }

	protected function rules(): array {
		return [
            'target_id' => 'required|int',
            'type' => 'int:0,127',
            'link_id' => 'int',
            'name' => 'required|string:0,50',
            'avatar' => 'string:0,200',
            'description' => 'string:0,200',
            'keywords' => 'string:0,255',
            'content' => '',
            'properties' => 'string:0,255',
            'start_at' => 'string',
            'end_at' => 'string',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'target_id' => 'Target Id',
            'type' => 'Type',
            'link_id' => 'Link Id',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'content' => 'Content',
            'properties' => 'Properties',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}