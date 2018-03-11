<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * Class TemplateModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $type
 * @property integer $cat_id
 * @property string $name
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class TemplateModel extends Model {
    public static function tableName() {
        return 'wechat_template';
    }

    protected function rules() {
        return [
            'type' => 'required|int:0,999',
            'cat_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => 'required',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'cat_id' => 'Cat Id',
            'name' => 'Name',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}