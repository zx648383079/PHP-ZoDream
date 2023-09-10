<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
 * Class PageQuestionModel
 * @property integer $id
 * @property integer $page_id
 * @property integer $evaluate_id
 * @property integer $question_id
 * @property integer $user_id
 * @property string $content
 * @property string $answer
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $max_score
 * @property integer $score
 * @property integer $answer_type
 * @property string $remark
 */
class PageQuestionEntity extends Entity {
    public static function tableName(): string {
        return 'exam_page_question';
    }
    protected function rules(): array {
        return [
            'page_id' => 'required|int',
            'evaluate_id' => 'required|int',
            'question_id' => 'required|int',
            'user_id' => 'required|int',
            'content' => '',
            'answer' => '',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
            'max_score' => 'int',
            'score' => 'int',
            'answer_type' => 'int:0,127',
            'remark' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'page_id' => 'Page Id',
            'evaluate_id' => 'Evaluate Id',
            'question_id' => 'Question Id',
            'user_id' => 'User Id',
            'content' => 'Content',
            'answer' => 'Answer',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}