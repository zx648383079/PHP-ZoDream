<?php
namespace Module\Template\Domain\Model;

use Module\Template\Domain\Entities\SitePageEntity;
use Zodream\Helpers\Json;

/**
 * Class PageModel
 * @package Module\Template
 * @property integer $id
 * @property integer $site_id
 * @property integer $component_id
 * @property integer $type
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $settings
 * @property integer $position
 * @property string $dependencies
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property SiteModel $site
 */
class SitePageModel extends SitePageEntity {


    public function site() {
        return $this->hasOne(SiteModel::class, 'id', 'site_id');
    }

    public function getSettingsAttribute() {
        $val = $this->getAttributeSource('settings');
        return empty($val) ? [] : Json::decode($val);
    }

    public function setSettingsAttribute($value) {
        $this->__attributes['settings'] = is_array($value) ? Json::encode($value) : $value;
    }

    public function setting($key, $default = null) {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
}