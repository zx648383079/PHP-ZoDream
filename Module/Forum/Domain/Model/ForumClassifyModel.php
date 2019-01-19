<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Zodream\Html\Tree;

/**
 * Class ForumClassifyModel
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property integer $forum_id
 * @property integer $position
 */
class ForumClassifyModel extends Model {
    public static function tableName() {
        return 'bbs_forum_classify';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'icon' => 'string:0,100',
            'forum_id' => 'int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'forum_id' => 'Forum Id',
            'position' => 'Position',
        ];
    }

}