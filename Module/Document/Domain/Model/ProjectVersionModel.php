<?php
declare(strict_types=1);
namespace Module\Document\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

class ProjectVersionModel extends Model {

    public static function tableName() {
        return 'doc_project_version';
    }

}