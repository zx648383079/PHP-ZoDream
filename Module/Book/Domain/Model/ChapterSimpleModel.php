<?php
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\ChapterEntity;

/**
 * Class ChapterSimpleModel
 * @package Module\Book\Domain\Model
 * @property integer $id
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 * @property integer $book_id
 * @property integer $parent_id
 * @property integer $status
 * @property string $source
 * @property integer $size
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ChapterSimpleModel extends ChapterEntity {

    public static function query() {
        return parent::query()->select(['id', 'title', 'book_id', 'parent_id', 'size', 'updated_at']);
    }
}