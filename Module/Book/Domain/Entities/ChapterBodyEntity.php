<?php
declare(strict_types=1);
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $content
 */
class ChapterBodyEntity extends Entity {
    public static function tableName(): string {
        return 'book_chapter_body';
    }

    protected function rules(): array {
        return [
            'content' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'content' => '内容',
        ];
    }
}