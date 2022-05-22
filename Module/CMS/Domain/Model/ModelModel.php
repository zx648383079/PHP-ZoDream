<?php
namespace Module\CMS\Domain\Model;

use Zodream\Helpers\Json;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $name
 * @property string $table
 * @property integer $type
 * @property integer $position
 * @property integer $child_model
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $edit_template
 * @property string $setting
 */
class ModelModel extends BaseModel {

    public static function tableName() {
        return 'cms_model';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'table' => 'required|string:0,100',
            'type' => 'int:0,9',
            'position' => 'int:0,999',
            'child_model' => 'int',
            'category_template' => 'string:0,20',
            'list_template' => 'string:0,20',
            'show_template' => 'string:0,20',
            'edit_template' => 'string:0,20',
            'setting' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'table' => '表名',
            'type' => '类型',
            'position' => '排序',
            'child_model' => '分级模型',
            'category_template' => '分类模板',
            'list_template' => '列表模板',
            'show_template' => '详情模板',
            'edit_template' => '编辑模板',
            'setting' => 'Setting',
        ];
    }

    public function fields() {
        return $this->hasMany(ModelFieldModel::class, 'model_id');
    }

    /**
     * @return ModelFieldModel[]
     */
    public function getFields() {
        $data = array();
        foreach ($this->fields as $item){
            /** @var $item ModelFieldModel */
            $data[$item->field] = $item;
        }
        return $data;
    }


    public function getSettingAttribute() {
        $setting = $this->getAttributeValue('setting');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setSettingAttribute($value) {
        $this->__attributes['setting'] = is_array($value) ?
            Json::encode($value) : $value;
    }

    public function setting(...$keys) {
        $data = $this->setting;
        foreach ($keys as $key) {
            if (empty($key)) {
                return $data;
            }
            if (empty($data) || !is_array($data)) {
                return null;
            }
            if (isset($data[$key]) || array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
            return null;
        }
        return $data;
    }

}