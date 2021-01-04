<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionWrongEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $question_id
 * @property integer $user_id
 * @property integer $frequency
 * @property integer $created_at
 * @property integer $updated_at
 */
class QuestionWrongEntity extends Entity {
	public static function tableName() {
        return 'exam_question_wrong';
    }

    public function rules() {
        return [
            'question_id' => 'required|int',
            'user_id' => 'required|int',
            'frequency' => 'int:0,99999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'question_id' => 'Question Id',
            'user_id' => 'User Id',
            'frequency' => 'Frequency',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}