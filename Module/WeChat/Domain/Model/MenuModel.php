<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * Class MenuModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $wid
 * @property string $name
 * @property string $type
 * @property string $content
 * @property string $pages
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class MenuModel extends Model {

    public static function tableName() {
        return 'wechat_menu';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'name' => 'required|string:3-100',
            'type' => 'required|string:3-100',
            'content' => 'required',
            'pages' => 'required',
            'parent_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'name' => 'Name',
            'type' => 'Type',
            'content' => 'Content',
            'pages' => 'Pages',
            'parent_id' => 'Parent Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}