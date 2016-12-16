<?php
namespace Domain\Model\Questionnaire;

use Domain\Model\Model;
/**
* 多选项
* @property integer $id
*/
class QuestionCheckboxModel extends Model {
	public static function tableName() {
        return 'question';
    }


}