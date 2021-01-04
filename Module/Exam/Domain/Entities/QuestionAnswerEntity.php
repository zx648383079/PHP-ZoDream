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
 * @property integer $created_at
 * @property integer $updated_at
 */
class QuestionAnswerEntity extends Entity {
	public static function tableName() {
        return 'exam_question_answer';
    }

    public function rules() {
        return [
            'question_id' => 'required|int',
            'user_id' => 'required|int',
            'content' => '',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'user_id' => 'User Id',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}