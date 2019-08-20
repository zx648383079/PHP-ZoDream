<?php
namespace Module\CMS\Domain\Model;
use Module\CMS\Domain\Fields\BaseField;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $field
 * @property integer $model_id
 * @property string $type
 * @property integer $length
 * @property integer $position
 * @property integer $form_type
 * @property integer $is_main
 * @property integer $is_required
 * @property integer $is_default
 * @property integer $is_system
 * @property integer $is_disable
 * @property string $match
 * @property string $tip_message
 * @property string $error_message
 * @property string $tab_name
 * @property string $setting
 * @property ModelModel $model
 */
class ModelFieldModel extends BaseModel {

    public $type_list = [
        'text' => '文本字段',
        'textarea' => '多行文本',
        'markdown' => 'Markdown',
        'editor' => '编辑器',
        'radio' => '单选按钮',
        'select' => '下拉选择',
        'switch' => '开关',
        'checkbox' => '复选框',
        'color' => '颜色选取',
        'email' => '邮箱字段',
        'password' => '密码字段',
        'url' => '链接字段',
        'ip' => 'IP字段',
        'number' => '数字字段',
        'date' => '日期时间',
        'file' => '单文件',
        'image' => '单图',
        'images' => '多图',
        'files' => '多文件',
        'linkage' => '联动菜单',
        'location' => '定位',
        'map' => '地图',
        'model' => '关联实体模型',
    ];

    public $match_list = [

    ];

    public static function tableName() {
        return 'cms_model_field';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'field' => 'required|string:0,100',
            'model_id' => 'required|int',
            'type' => 'string:0,20',
            'length' => 'int:0,999',
            'position' => 'int:0,999',
            'form_type' => 'int:0,999',
            'is_main' => 'int:0,9',
            'is_required' => 'int:0,9',
            'is_default' => 'int:0,9',
            'is_disable' => 'int:0,9',
            'is_system' => 'int:0,9',
            'match' => 'string:0,255',
            'tip_message' => 'string:0,255',
            'error_message' => 'string:0,255',
            'tab_name' => 'string:0,4',
            'setting' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'field' => '字段名',
            'model_id' => 'Model Id',
            'type' => '类型',
            'is_main' => '主表',
            'length' => '字段长度',
            'position' => '排序',
            'form_type' => '表单类型',
            'is_required' => '是否必填',
            'is_default' => '默认值',
            'is_system' => '系统字段',
            'match' => '匹配规则',
            'tip_message' => '提示信息',
            'error_message' => '错误提示',
            'setting' => '其他设置',
        ];
    }

    public function getSettingAttribute() {
        $setting = $this->getAttributeValue('setting');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setSettingAttribute($value) {
        $this->__attributes['setting'] = is_array($value) ? Json::encode($value) : '';
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

    public function model() {
        return $this->hasOne(ModelModel::class, 'id', 'model_id');
    }

    /**
     * 获取所有的分组标签
     * @param $model_id
     * @return array
     */
    public static function tabItems($model_id) {
        $tab_list = static::where('model_id', $model_id)->pluck('tab_name');
        $data = ['基本', '高级'];
        foreach ($tab_list as $item) {
            $item = trim($item);
            if (empty($item) || in_array($item, $data)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 对属性进行分组
     * @param $model_id
     * @return array
     */
    public static function tabGroups($model_id) {
        $tab_list = self::tabItems($model_id);
        $data = [];
        foreach ($tab_list as $i => $item) {
            $data[$item] = [
                'active' => $i < 1,
                'fields' => []
            ];
        }
        $field_list = ModelFieldModel::where('model_id', $model_id)->orderBy([
            'position' => 'asc',
            'id' => 'asc'
        ])->all();
        foreach ($field_list as $item) {
            $name = $item->tab_name;
            if (empty($name) || !in_array($name, $tab_list)) {
                $name = $item->is_main > 0 ? $tab_list[0] : $tab_list[1];
            }
            $data[$name]['fields'][] = $item;
        }
        return $data;
    }
}