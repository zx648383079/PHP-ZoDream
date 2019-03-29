<?php
namespace Module\CMS\Domain\Model;

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
            'groups' => '',
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
            'name' => '目录名',
            'title' => '名称',
            'type' => '类型',
            'model_id' => '模型',
            'parent_id' => '上级',
            'keywords' => '关键词',
            'description' => '简介',
            'image' => '图片',
            'content' => '内容',
            'url' => '链接',
            'position' => '排序',
            'groups' => '分组',
            'category_template' => '分类模板',
            'list_template' => '列表模板',
            'show_template' => '详情模板',
            'setting' => 'Setting',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

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
        return new Tree(static::query()->all());
    }

}