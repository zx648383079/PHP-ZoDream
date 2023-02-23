<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;

/**
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $theme_weight_id 部件名
 * @property integer $theme_style_id 部件名
 * @property string $title
 * @property string $content
 * @property string $settings
 * @property boolean $is_share 是否通用
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteWeightModel extends Model {
    public static function tableName() {
        return 'tpl_site_weight';
    }

    protected function rules() {
        return [
            'site_id' => 'required|int',
            'theme_weight_id' => 'required|int',
            'theme_style_id' => 'int',
            'title' => 'string:0,200',
            'content' => '',
            'settings' => '',
            'is_share' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'theme_weight_id' => 'Weight Id',
            'theme_style_id' => 'Style Id',
            'title' => 'Title',
            'content' => 'Content',
            'settings' => 'Settings',
            'is_share' => 'Is Share',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function getSettingsAttribute() {
        $val = $this->getAttributeSource('settings');
        return empty($val) ? [] : Json::decode($val);
    }

    public function setSettingsAttribute($value) {
        $this->__attributes['settings'] = is_array($value) ? Json::encode($value) : $value;
    }

    public function setting($key, $default = null) {
        return Arr::get($this->settings, $key, $default);
    }
}