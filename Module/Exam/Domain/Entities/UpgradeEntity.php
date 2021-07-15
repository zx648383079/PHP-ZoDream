<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class UpgradeEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $course_id
 * @property integer $course_grade
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property integer $updated_at
 * @property integer $created_at
 */
class UpgradeEntity extends Entity {
    public static function tableName() {
        return 'exam_upgrade';
    }

    protected function rules() {
        return [
            'course_id' => 'required|int',
            'course_grade' => 'int',
            'name' => 'required|string:0,100',
            'icon' => 'string:0,100',
            'description' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'course_id' => 'Course Id',
            'course_grade' => 'Course Grade',
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}