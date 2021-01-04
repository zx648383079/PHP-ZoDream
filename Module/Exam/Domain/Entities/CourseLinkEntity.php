<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class CourseLinkEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $course_id
 * @property integer $link_id
 * @property string $title
 */
class CourseLinkEntity extends Entity {
    public static function tableName() {
        return 'exam_course_link';
    }

    protected $primaryKey = '';

    public function rules() {
        return [
            'course_id' => 'required|int',
            'link_id' => 'required|int',
            'title' => 'string:0,100',
        ];
    }

    protected function labels() {
        return [
            'course_id' => 'Course Id',
            'link_id' => 'Link Id',
            'title' => 'Title',
        ];
    }

}