<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\PageQuestionEntity;

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
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageQuestionModel extends PageQuestionEntity {

    public function question() {
        return $this->hasOne(QuestionModel::class, 'id', 'question_id');
    }
}