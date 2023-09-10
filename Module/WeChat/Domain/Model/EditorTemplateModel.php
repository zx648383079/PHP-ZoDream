<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property integer $type
 * @property integer $cat_id
 * @property string $name
 * @property string $content
 * @property integer $updated_at
 * @property integer $created_at
 */
class EditorTemplateModel extends Model {

    public static $type_list = [
        '标题样式',
        '正文样式',
        '图文样式',
        '引导样式',
        '分割线',
        '二维码',
        '音频样式',
        '视频样式',
        '图标样式',

        91 => '节日模板',
        92 => '行业模板'
    ];

    public static function tableName(): string {
        return 'wechat_editor_template';
    }

    protected function rules(): array {
        return [
            'type' => 'int:0,127',
            'cat_id' => 'int',
            'name' => 'required|string:0,100',
            'content' => 'required',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'type' => '类型',
            'cat_id' => '分类',
            'name' => '名称',
            'content' => '内容',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}