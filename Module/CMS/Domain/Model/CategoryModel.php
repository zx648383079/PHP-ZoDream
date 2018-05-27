<?php
namespace Module\CMS\Domain\Model;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $type
 * @property integer $model_id
 * @property integer $parent_id
 * @property string $keywords
 * @property string $description
 * @property string $image
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
class CategoryModel extends BaseModel {
    const TYPE_CONTENT = 0; //内容
    const TYPE_PAGE = 1; //单页
    const TYPE_LINK = 2; //外链

    public static function tableName() {
        return 'cms_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'title' => 'required|string:0,100',
            'type' => 'int:0,9',
            'model_id' => 'int',
            'parent_id' => 'int',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'image' => 'string:0,100',
            'content' => '',
            'url' => 'string:0,100',
            'position' => 'int:0,999',
            'is_menu' => 'int:0,9',
            'category_template' => 'string:0,20',
            'list_template' => 'string:0,20',
            'show_template' => 'string:0,20',
            'setting' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'title' => 'Title',
            'type' => 'Type',
            'model_id' => 'Model Id',
            'parent_id' => 'Parent Id',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'image' => 'Image',
            'content' => 'Content',
            'url' => 'Url',
            'position' => 'Position',
            'is_menu' => 'Is Menu',
            'category_template' => 'Category Template',
            'list_template' => 'List Template',
            'show_template' => 'Show Template',
            'setting' => 'Setting',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ModelModel
     */
    public function getModel() {
        return $this->hasOne(ModelModel::class, 'id', 'model_id');
    }

    public function getUrl() {
        return $this->url;
    }
}