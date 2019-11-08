<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\CourseEntity;
use Zodream\Html\Tree;

/**
 * Class CourseEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property string $description
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CourseModel extends CourseEntity {

    public function children() {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public static function tree() {
        return new Tree(static::query()->get());
    }
}