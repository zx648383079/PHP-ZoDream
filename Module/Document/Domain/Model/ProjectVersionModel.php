<?php
declare(strict_types=1);
namespace Module\Document\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ProjectVersionModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $updated_at
 * @property integer $created_at
 */
class ProjectVersionModel extends Model {

    public static function tableName(): string {
        return 'doc_project_version';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,20',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}