<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class CourseGradeEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $course_id
 * @property string $name
 * @property integer $grade
 */
class CourseGradeEntity extends Entity {
    public static function tableName() {
        return 'exam_course_grade';
    }

    protected function rules() {
        return [
            'course_id' => 'required|int',
            'name' => 'required|string:0,30',
            'grade' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'course_id' => 'Course Id',
            'name' => 'Name',
            'grade' => 'Grade',
        ];
    }
}