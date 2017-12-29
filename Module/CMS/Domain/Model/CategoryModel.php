<?php
namespace Module\CMS\Domain\Model;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property integer $model_id
 * @property string $name
 * @property string $image
 * @property string $content
 * @property string $url
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $position
 * @property integer $is_menu
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $setting
 * @property integer $update_at
 * @property integer $create_at
 */
class CategoryModel extends BaseModel {
    const TYPE_CONTENT = 0; //内容
    const TYPE_PAGE = 1; //单页
    const TYPE_LINK = 2; //外链

    public static function tableName() {
        return 'category';
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