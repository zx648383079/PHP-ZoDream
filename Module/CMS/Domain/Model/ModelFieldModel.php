<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\ModelFieldEntity;
use Zodream\Helpers\Json;

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
 * @property integer $is_search
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
class ModelFieldModel extends ModelFieldEntity {

    public function getSettingAttribute() {
        $setting = $this->getAttributeSource('setting');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setSettingAttribute($value) {
        $this->setAttributeSource('setting', is_array($value) ? Json::encode($value) : '');
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

}