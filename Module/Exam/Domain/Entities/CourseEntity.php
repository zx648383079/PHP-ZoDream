<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

class CourseEntity extends Entity {
    public static function tableName() {
        return 'exam_course';
    }
}