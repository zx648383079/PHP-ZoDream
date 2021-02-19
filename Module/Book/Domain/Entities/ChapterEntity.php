<?php
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class ChapterEntity
 * @package Module\Book\Domain\Entities
 * @property integer $id
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 * @property integer $book_id
 * @property integer $parent_id
 * @property integer $status
 * @property integer $position
 * @property string $source
 * @property integer $size
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ChapterEntity extends Entity {
    public static function tableName() {
        return 'book_chapter';
    }

    protected function rules() {
        return [
            'book_id' => 'required|int',
            'title' => 'required|string:0,200',
            'parent_id' => 'int',
            'price' => 'int',
            'status' => 'int:0,127',
            'position' => 'int:0,127',
            'size' => 'int',
            'source' => 'string:0,200',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'book_id' => '书',
            'title' => '标题',
            'parent_id' => '上级',
            'position' => '排序',
            'status' => '状态',
            'source' => '来源',
            'size' => '字数',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }
}