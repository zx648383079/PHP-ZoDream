<?php
namespace Domain\Model\Questionnaire;

use Domain\Model\Model;
/**
 * Class QuestionModel
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $user_id
 * @property integer $status
 * @property integer $count
 * @property integer $update_at
 * @property integer $create_at
 */
class QuestionModel extends Model {

    const TYPE_RADIO = 0;     //单选
    const TYPE_CHECKBOX = 1;  //多选
    const TYPE_JUDGE = 2;     //判断
    const TYPE_FILL = 3;      //填空
    const TYPE_QUESTION = 4;  //问答题

	public static function tableName() {
        return 'question';
    }


}