<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ProjectModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $environment
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProjectModel extends Model {

    public static function tableName() {
        return 'doc_project';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,35',
            'description' => 'string:0,255',
            'environment' => '',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'environment' => 'Environment',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getEnvironmentsAttribute() {
        return empty($this->environment) ? [] : Json::decode($this->environment);
    }
}