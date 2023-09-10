<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionAnswerEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $question_id
 * @property integer $user_id
 * @property string $content
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $answer_type
 * @property string $answer
 */
class QuestionAnswerEntity extends Entity {
	public static function tableName(): string {
        return 'exam_question_answer';
    }

    protected function rules(): array {
        return [
            'question_id' => 'required|int',
            'user_id' => 'required|int',
            'content' => '',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
            'answer_type' => 'int:0,127',
            'answer' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'user_id' => 'User Id',
            'content' => 'Content',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'answer_type' => 'Answer Type',
            'answer' => 'Answer',
        ];
    }
}