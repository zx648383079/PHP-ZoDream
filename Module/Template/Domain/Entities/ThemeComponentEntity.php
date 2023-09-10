<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $cat_id
 * @property integer $user_id
 * @property integer $price
 * @property integer $type
 * @property string $author
 * @property string $version
 * @property integer $status
 * @property integer $editable
 * @property string $path
 * @property string $alias_name
 * @property integer $updated_at
 * @property integer $created_at
 */
class ThemeComponentEntity extends Entity {
    public static function tableName(): string {
        return 'tpl_theme_component';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'cat_id' => 'required|int',
            'user_id' => 'required|int',
            'price' => 'int',
            'type' => 'int:0,127',
            'author' => 'string:0,20',
            'version' => 'string:0,10',
            'status' => 'int:0,127',
            'editable' => 'int:0,127',
            'path' => 'required|string:0,200',
            'alias_name' => 'required|string:0,30',
            'dependencies' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'cat_id' => 'Cat Id',
            'user_id' => 'User Id',
            'price' => 'Price',
            'type' => 'Type',
            'author' => 'Author',
            'version' => 'Version',
            'status' => 'Status',
            'path' => 'Path',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}