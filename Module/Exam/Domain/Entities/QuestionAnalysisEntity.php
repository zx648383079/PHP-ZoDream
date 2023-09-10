<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionAnalysisEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $question_id
 * @property integer $type
 * @property string $content
 */
class QuestionAnalysisEntity extends Entity {
	public static function tableName(): string {
        return 'exam_question_analysis';
    }

    protected function rules(): array {
        return [
            'question_id' => 'required|int',
            'type' => 'int:0,127',
            'content' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'type' => 'Type',
            'content' => 'Content',
        ];
    }
}