<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Domain\Entities\SiteWeightEntity;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;

/**
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $component_id
 * @property string $title
 * @property string $content
 * @property string $settings
 * @property boolean $is_share 是否通用
 * @property integer $style_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteWeightModel extends SiteWeightEntity {

    public function getSettingsAttribute() {
        $val = $this->getAttributeSource('settings');
        return empty($val) ? [] : Json::decode($val);
    }

    public function setSettingsAttribute($value) {
        $this->setAttributeSource('settings', is_array($value) ? Json::encode($value) : $value);
    }

    public function setting(string $key, mixed $default = null): mixed {
        return Arr::get($this->settings, $key, $default);
    }
}