<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $description
 * @property integer $user_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthorEntity extends Entity {
    public static function tableName(): string {
        return 'book_author';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'avatar' => 'string:0,200',
            'description' => 'string:0,200',
            'user_id' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '笔名',
            'avatar' => '头像',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}