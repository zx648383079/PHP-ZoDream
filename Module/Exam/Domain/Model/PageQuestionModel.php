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
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $max_score
 * @property integer $score
 * @property integer $answer_type
 * @property string $remark
 * @property QuestionModel $question
 */
class PageQuestionModel extends PageQuestionEntity {

    const STATUS_NONE = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

    public function question() {
        return $this->hasOne(QuestionModel::class, 'id', 'question_id');
    }

    public function format($i = 0, $finished = true) {
        $data = $this->question->format($i,
            $this->content,
            $finished);
        $data['log'] = $this->toArray();
        unset($data['log']['question']);
        return $data;
    }
}