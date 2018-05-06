<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Html\Tree;

/**
 * Class ApiModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $method
 * @property string $uri
 * @property integer $project_id
 * @property string $description
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ApiModel extends Model {

    public static function tableName() {
        return 'doc_api';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,35',
            'method' => 'required|string:0,10',
            'uri' => 'required|string:0,255',
            'project_id' => 'required|int',
            'description' => 'string:0,255',
            'parent_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'method' => 'Method',
            'uri' => 'Uri',
            'project_id' => 'Project Id',
            'description' => 'Description',
            'parent_id' => 'Parent Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public  static function getTree($project_id) {
        $data = self::where('project_id', $project_id)->select('id', 'name', 'parent_id')->all();
        return (new Tree($data))->makeTree();
    }
}