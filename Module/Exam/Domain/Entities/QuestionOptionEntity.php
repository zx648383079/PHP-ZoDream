<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionOptionEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property string $content
 * @property integer $question_id
 * @property integer $type
 * @property integer $is_right
 */
class QuestionOptionEntity extends Entity {
	public static function tableName(): string {
        return 'exam_question_option';
    }

    public function rules(): array {
        return [
            'content' => 'required|string:0,255',
            'question_id' => 'required|int',
            'type' => 'int:0,9',
            'is_right' => 'int:0,9',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'question_id' => 'Question Id',
            'type' => 'Type',
            'is_right' => 'Is Right',
        ];
    }

}