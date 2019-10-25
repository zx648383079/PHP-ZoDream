<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

class CourseLinkEntity extends Entity {
    public static function tableName() {
        return 'exam_course_link';
    }
}