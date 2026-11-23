<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 */
class CategoryEntity extends Entity {
    public static function tableName(): string {
        return 'book_category';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '分类',
            'created_at' => '创建时间',
        ];
    }
}