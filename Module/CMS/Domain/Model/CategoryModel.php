<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\FuncHelper;
use Zodream\Helpers\Json;
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
        $tpl = $this->getAttributeSource('category_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1) {
            $model = FuncHelper::model($this->model_id);
            return $model['category_template'] ?? '';
        }
        return null;
    }

    public function getListTemplateAttribute() {
        $tpl = $this->getAttributeSource('list_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1) {
            $model = FuncHelper::model($this->model_id);
            return $model['list_template'] ?? '';
        }
        return null;
    }

    public function getShowTemplateAttribute() {
        $tpl = $this->getAttributeSource('show_template');
        if (!empty($tpl)) {
            return $tpl;
        }
        if ($this->type < 1) {
            $model = FuncHelper::model($this->model_id);
            return $model['show_template'] ?? '';
        }
        return null;
    }

    public function getSettingAttribute() {
        $setting = $this->getAttributeSource('setting');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setSettingAttribute($value) {
        $this->setAttributeSource('setting', is_array($value) ?
            Json::encode($value) : $value);
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