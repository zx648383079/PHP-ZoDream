<?php
namespace Module\CMS\Domain\Entities;

use Domain\Entities\Entity;
use Module\CMS\Domain\Repositories\CMSRepository;

/**
 * Class CategoryEntity
 * @package Module\Cms\Domain\Entities
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $type
 * @property integer $model_id
 * @property integer $parent_id
 * @property string $keywords
 * @property string $description
 * @property string $image
 * @property string $thumb
 * @property string $content
 * @property string $url
 * @property integer $position
 * @property integer $is_menu
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $setting
 * @property integer $created_at
 * @property integer $updated_at
 */
class CategoryEntity extends Entity {
    const int TYPE_CONTENT = 0; //内容
    const int TYPE_PAGE = 1; //单页
    const int TYPE_LINK = 2; //外链

    public static function tableName(): string {
        return 'cms_category_'.CMSRepository::siteId();
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'title' => 'required|string:0,100',
            'type' => 'int:0,9',
            'model_id' => 'int',
            'parent_id' => 'int',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'image' => 'string:0,100',
            'thumb' => 'string:0,100',
            'content' => '',
            'url' => 'string:0,100',
            'position' => 'int:0,999',
            'groups' => '',
            'category_template' => 'string:0,20',
            'list_template' => 'string:0,20',
            'show_template' => 'string:0,20',
            'setting' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '目录名',
            'title' => '名称',
            'type' => '类型',
            'model_id' => '模型',
            'parent_id' => '上级',
            'keywords' => '关键词',
            'description' => '简介',
            'thumb' => '缩略图',
            'image' => '主图',
            'content' => '内容',
            'url' => '链接',
            'position' => '排序',
            'groups' => '分组',
            'category_template' => '分类模板',
            'list_template' => '列表模板',
            'show_template' => '详情模板',
            'setting' => 'Setting',
            'setting.open_comment' => '开启评论',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}