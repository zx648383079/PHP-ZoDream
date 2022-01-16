<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 */
class EditorTemplateCategoryModel extends Model {

    public static function tableName() {
        return 'wechat_editor_template_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'parent_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
        ];
    }
}