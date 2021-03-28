<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\QuestionAnswerEntity;

/**
 * Class QuestionAnswerModel
 * @package Module\Exam\Domain\Model
 * @property integer $id
 * @property integer $question_id
 * @property integer $user_id
 * @property string $content
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class QuestionAnswerModel extends QuestionAnswerEntity {

}