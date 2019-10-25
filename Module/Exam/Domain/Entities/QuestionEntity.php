<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
* Class QuestionModel
* @property integer $id
* @property string $title
* @property string $content
* @property integer $user_id
* @property integer $status
* @property integer $count
* @property integer $update_at
* @property integer $create_at
*/
class QuestionEntity extends Entity {
	public static function tableName() {
        return 'exam_question';
    }

    protected function rules() {
		return array (
		  'title' => 'required|string:3-200',
		  'content' => '',
		  'user_id' => 'int',
		  'status' => 'int',
		  'count' => 'int',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'status' => 'Status',
		  'count' => 'Count',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}

    /**
     * @param string $content
     * @param int $parentId
     * @return QuestionAnswerModel
     */
	public function addAnswer($content, $parentId = 0) {
        $model = new QuestionAnswerModel();
        $model->content = $content;
        $model->parent_id = $parentId;
        $model->question_id = $this->id;
        $model->save();
        return $model;
    }


}