<?php
namespace Module\Exam\Domain\Model;


use Module\Exam\Domain\Entities\QuestionEntity;
use Module\Exam\Domain\Model\CourseModel;

class QuestionModel extends QuestionEntity {

    public function course() {
        return $this->hasOne(CourseModel::class, 'id', 'course_id');
    }
}