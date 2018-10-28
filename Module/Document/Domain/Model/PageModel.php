<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Html\Tree;

/**
 * Class PageModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $project_id
 * @property integer $parent_id
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageModel extends Model {

    public static function tableName() {
        return 'doc_page';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,35',
            'project_id' => 'required|int',
            'parent_id' => 'int',
            'content' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'project_id' => '项目',
            'content' => '文档内容',
            'parent_id' => '上级',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public  static function getTree($project_id) {
        $data = self::where('project_id', $project_id)->select('id', 'name', 'parent_id')->asArray()->all();
        return (new Tree($data))->makeTree();
    }
}