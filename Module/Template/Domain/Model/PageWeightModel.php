<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Domain\Weight;
use Module\Template\Domain\WeightProperty;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;


/**
 * Class PageWeightModel
 * @package Module\Template
 * @property integer $id
 * @property integer $page_id
 * @property integer $site_id
 * @property integer $theme_weight_id 部件名
 * @property integer $theme_style_id 部件名
 * @property integer $parent_id
 * @property integer $position
 * @property string $title
 * @property string $content
 * @property string $settings
 * @property boolean $is_share 是否通用
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ThemeWeightModel $weight
 * @property WeightProperty $properties
 */
class PageWeightModel extends Model {

    public static function tableName() {
        return 'tpl_page_weight';
    }

    protected function rules() {
        return [
            'page_id' => 'required|int',
            'site_id' => 'required|int',
            'theme_weight_id' => 'required|int',
            'theme_style_id' => 'int',
            'parent_id' => 'int',
            'position' => 'int',
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
            'page_id' => 'Page Id',
            'site_id' => 'Site Id',
            'theme_weight_id' => 'Weight Id',
            'theme_style_id' => 'Style Id',
            'parent_id' => 'Parent Id',
            'position' => 'Position',
            'title' => 'Title',
            'content' => 'Content',
            'settings' => 'Settings',
            'is_share' => 'Is Share',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function weight() {
        return $this->hasOne(ThemeWeightModel::class,
            'id', 'theme_weight_id');
    }

    public function getSettingsAttribute() {
        $val = $this->getAttributeSource('settings');
        return empty($val) ? [] : Json::decode($val);
    }

    public function setSettingsAttribute($value) {
        $this->__attributes['settings'] = is_array($value) ? Json::encode($value) : $value;
    }

    public function getPropertiesAttribute() {
        return WeightProperty::create($this);
    }

    public function hasExtInfo($ext) {
        return false;
    }

    public function setting($key, $default = null) {
        return Arr::get($this->settings, $key, $default);
    }

    public function saveFromPost() {
        $disable = ['id', 'page_id', 'theme_weight_id'];
        $maps = ['parent_id', 'theme_style_id',
            'position', 'title', 'content', 'is_share', 'settings'];
        $data = (new Weight($this))->newWeight()->parseConfigs();
        $args = [
            'settings' => $this->getSettingsAttribute()
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $disable)) {
                continue;
            }
            if ($key === 'settings') {
                $args['settings'] = array_merge($args['settings'], $value);
                continue;
            }
            if (in_array($key, $maps)) {
                $args[$key] = $value;
                continue;
            }
            $args['settings'][$key] = $value;
        }
        $this->set($args);
        $this->save();
        return $this;
    }

    /**
     * 删除自身及子代
     * @param $id
     * @return boolean
     */
    public static function removeSelfAndChild($id) {
        if ($id < 1) {
            return true;
        }
        $data = [$id];
        $parents = $data;
        while (true) {
            $parents = self::whereIn('parent_id', $parents)
                ->pluck('id');
            if (empty($parents)) {
                break;
            }
            $data = array_merge($data, $parents);
        }
        return self::whereIn('id', $data)->delete();
    }
}