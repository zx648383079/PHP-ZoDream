<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionAnswerEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $question_id
 * @property string $content
 * @property string $analysis
 */
class QuestionAnswerEntity extends Entity {
	public static function tableName() {
        return 'exam_question_answer';
    }

    protected function rules() {
        return [
            'question_id' => 'required|int',
            'content' => '',
            'analysis' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'content' => 'Content',
            'analysis' => 'Analysis',
        ];
    }
}