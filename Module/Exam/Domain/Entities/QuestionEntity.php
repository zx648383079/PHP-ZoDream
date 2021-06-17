<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
* Class QuestionModel
 * @property string $id
 * @property string $title
 * @property string $image
 * @property string $course_id
 * @property string $parent_id
 * @property string $type
 * @property string $easiness
 * @property string $content
 * @property string $dynamic
 * @property string $answer
 * @property string $updated_at
 * @property string $created_at
 * @property string $material_id
*/
class QuestionEntity extends Entity {
	public static function tableName() {
        return 'exam_question';
    }

    public function rules() {
        return [
            'title' => 'required|string:0,255',
            'image' => 'string:0,200',
            'course_id' => 'required',
            'parent_id' => '',
            'type' => '',
            'easiness' => '',
            'content' => '',
            'dynamic' => '',
            'answer' => '',
            'updated_at' => '',
            'created_at' => '',
            'material_id' => '',
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
            'content' => '内容',
            'dynamic' => '可变内容',
            'answer' => '答案',
            'analysis' => '解析',
            'created_at' => 'int',
            'updated_at' => 'int',
            'material_id' => '资源',
        ];
    }

}