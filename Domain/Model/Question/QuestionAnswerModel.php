<?php
namespace Domain\Model\Question;

use Domain\Model\Model;
/**
* Class QuestionAnswerModel
* @property integer $id
* @property integer $question_id
* @property string $content
* @property integer $user_id
* @property integer $status
* @property integer $parent_id
* @property integer $update_at
* @property integer $create_at
*/
class QuestionAnswerModel extends Model {
	public static function tableName() {
        return 'question_answer';
    }

    protected function rules() {
		return array (
		  'question_id' => 'int',
		  'content' => '',
		  'user_id' => 'int',
		  'status' => 'int',
		  'parent_id' => 'int',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'question_id' => 'Question Id',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'status' => 'Status',
		  'parent_id' => 'Parent Id',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}