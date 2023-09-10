<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class UpgradePathEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $course_grade
 */
class UpgradePathEntity extends Entity {
    public static function tableName(): string {
        return 'exam_upgrade_path';
    }

    protected function rules(): array {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'course_grade' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'course_grade' => 'Course Grade',
        ];
    }


}