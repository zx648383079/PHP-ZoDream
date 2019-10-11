<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\CategoryEntity;
use Zodream\Helpers\Tree as TreeHelper;
use Zodream\Html\Tree;

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
 * @property ModelModel $model
 */
class CategoryModel extends CategoryEntity {

    public function model() {
        return $this->hasOne(ModelModel::class, 'id', 'model_id');
    }

    public function getUrl() {
        return $this->url;
    }

    public function setGroupsAttribute($value) {
        $this->__attributes['groups'] = is_array($value) ? implode(',', $value) : $value;
    }

    public function getCategoryTemplateAttribute() {
        $tpl = $this->getAttributeValue('category_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1 && $this->model) {
            return $this->model->category_template;
        }
        return null;
    }

    public function getListTemplateAttribute() {
        $tpl = $this->getAttributeValue('list_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1 && $this->model) {
            return $this->model->list_template;
        }
        return null;
    }

    public function getShowTemplateAttribute() {
        $tpl = $this->getAttributeValue('show_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1 && $this->model) {
            return $this->model->show_template;
        }
        return null;
    }

    /**
     * @return Tree
     * @throws \Exception
     */
    public static function tree() {
        return new Tree(static::query()->orderBy('position', 'asc')->all());
    }


    public static function getChildrenWithParent($id) {
        $data = TreeHelper::getTreeChild(self::query()->orderBy('parent_id', 'asc')
            ->orderBy('id', 'asc')->get('id', 'parent_id'), $id);
        $data[] = $id;
        return $data;
    }
}