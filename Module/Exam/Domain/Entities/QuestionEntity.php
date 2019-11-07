<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
* Class QuestionModel
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $course_id
 * @property integer $parent_id
 * @property integer $type
 * @property integer $easiness
 * @property integer $created_at
 * @property integer $updated_at
*/
class QuestionEntity extends Entity {
	public static function tableName() {
        return 'exam_question';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,255',
            'image' => 'string:0,200',
            'course_id' => 'required|int',
            'parent_id' => 'int',
            'type' => 'int:0,9',
            'easiness' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'title' => '题目',
            'image' => '图片',
            'course_id' => 'required|int',
            'parent_id' => 'int',
            'type' => '类型',
            'easiness' => '难易程度',
            'analysis' => '解析',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

}