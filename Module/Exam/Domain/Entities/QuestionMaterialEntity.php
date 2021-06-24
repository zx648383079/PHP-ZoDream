<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionMaterialEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $course_id
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property string $content
 */
class QuestionMaterialEntity extends Entity {
	public static function tableName() {
        return 'exam_question_material';
    }

    protected function rules() {
        return [
            'course_id' => 'required|int',
            'title' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'type' => 'int:0,127',
            'content' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'course_id' => 'Course Id',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'content' => 'Content',
        ];
    }
}