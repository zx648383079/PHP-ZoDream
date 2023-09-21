<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\ModelEntity;
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
class ModelModel extends ModelEntity {

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
        $setting = $this->getAttributeSource('setting');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setSettingAttribute($value) {
        $this->setAttributeSource('setting', is_array($value) ?
            Json::encode($value) : $value);
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