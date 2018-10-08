<?php
namespace Module\Bug\Domain\Model;

use Domain\Model\Model;

/**
 * Class BugModel
 * @package Module\Bug\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $uri
 * @property integer $grade
 * @property string $related
 * @property string $description
 * @property string $check_rule
 * @property string $solution
 * @property string $source
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class BugModel extends Model {

    public $type_list = [
        'WEB'
    ];

    public static function tableName() {
        return 'bug';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,255',
            'type' => 'int:0,9',
            'uri' => 'string:0,255',
            'grade' => 'int:0,99',
            'related' => 'string:0,255',
            'description' => 'string:0,255',
            'check_rule' => 'string:0,255',
            'solution' => 'string:0,255',
            'source' => 'string:0,255',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'type' => 'Type',
            'uri' => 'Uri',
            'grade' => 'Grade',
            'related' => 'Related',
            'description' => 'Description',
            'check_rule' => 'Check Rule',
            'solution' => 'Solution',
            'source' => 'Source',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}