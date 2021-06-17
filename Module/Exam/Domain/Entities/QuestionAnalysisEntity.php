<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionAnalysisEntity
 * @package Module\Exam\Domain\Entities
 * @property string $id
 * @property string $question_id
 * @property string $type
 * @property string $content
 */
class QuestionAnalysisEntity extends Entity {
	public static function tableName() {
        return 'exam_question_analysis';
    }

    protected function rules() {
        return [
            'question_id' => 'required',
            'type' => '',
            'content' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'type' => 'Type',
            'content' => 'Content',
        ];
    }
}