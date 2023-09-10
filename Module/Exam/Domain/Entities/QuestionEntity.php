<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
* Class QuestionModel
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $parent_id
 * @property integer $course_id
 * @property integer $course_grade
 * @property integer $type
 * @property integer $easiness
 * @property string $content
 * @property string $dynamic
 * @property string $answer
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $material_id
 * @property integer $user_id
 * @property integer $status
*/
class QuestionEntity extends Entity {
	public static function tableName(): string {
        return 'exam_question';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,255',
            'image' => 'string:0,200',
            'course_id' => 'required|int',
            'course_grade' => 'int',
            'parent_id' => 'int',
            'type' => 'int:0,127',
            'easiness' => 'int:0,127',
            'content' => '',
            'dynamic' => '',
            'answer' => '',
            'updated_at' => 'int',
            'created_at' => 'int',
            'material_id' => 'int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels(): array {
        return [
            'title' => '题目',
            'image' => '图片',
            'course_id' => 'required|int',
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