<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * Class MediaTemplateModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $type
 * @property integer $category
 * @property string $name
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class MediaTemplateModel extends Model {

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

    public static function tableName() {
        return 'wechat_media_template';
    }

    protected function rules() {
        return [
            'type' => 'int:0,999',
            'category' => 'int',
            'name' => 'required|string:0,100',
            'content' => 'required',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => '类型',
            'category' => '分类',
            'name' => '名称',
            'content' => '内容',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}