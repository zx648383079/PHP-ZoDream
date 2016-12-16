<?php
namespace Domain\Model\Questionnaire;

use Domain\Model\Model;
/**
* 单选项
* @property integer $id
*/
class QuestionRadioModel extends Model {
	public static function tableName() {
        return 'question';
    }


}