<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\QuestionMaterialEntity;

/**
 * Class QuestionMaterialModel
 * @package Module\Exam\Domain\Model
 * @property integer $id
 * @property integer $course_id
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property string $content
 */
class QuestionMaterialModel extends QuestionMaterialEntity {

}