<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
class QuestionAnswerEntity extends Entity {
	public static function tableName() {
        return 'exam_question_answer';
    }
}