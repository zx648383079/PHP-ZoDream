<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class QuestionMaterialEntity
 * @package Module\Exam\Domain\Entities
 * @property string $id
 * @property string $course_id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $content
 */
class QuestionMaterialEntity extends Entity {
	public static function tableName() {
        return 'exam_question_material';
    }

    protected function rules() {
        return [
            'course_id' => 'required',
            'title' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'type' => '',
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