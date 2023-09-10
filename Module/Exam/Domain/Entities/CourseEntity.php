<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class CourseEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property string $description
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CourseEntity extends Entity {
    public static function tableName(): string {
        return 'exam_course';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'thumb' => 'string:0,200',
            'description' => 'string:0,200',
            'parent_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '科目名',
            'thumb' => '图标',
            'description' => '简介',
            'parent_id' => '上级科目',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}