<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Zodream\Html\Tree;

class PageModel extends Model {

    public static function tableName() {
        return 'doc_page';
    }

    public  static function getTree($project_id) {
        $data = self::where('project_id', $project_id)->select('id', 'name', 'parent_id')->asArray()->all();
        return (new Tree($data))->makeTree();
    }
}