<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class SiteModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $logo
 * @property string $theme
 * @property integer $match_type
 * @property string $match_rule
 * @property integer $is_default
 * @property string $options
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteModel extends Model {

    const MATCH_TYPE_DOMAIN = 0;
    const MATCH_TYPE_PATH = 1;

    public static function tableName() {
        return 'cms_site';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,255',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'logo' => 'string:0,255',
            'theme' => 'required|string:0,100',
            'match_type' => 'int:0,127',
            'match_rule' => 'string:0,100',
            'is_default' => 'int:0,127',
            'options' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => '站点标题',
            'keywords' => '关键词',
            'description' => '简介',
            'logo' => 'Logo',
            'theme' => '主题',
            'match_type' => '匹配类型',
            'match_rule' => '匹配规则',
            'is_default' => '是否为默认站点',
            'options' => 'Options',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getOptionsAttribute() {
        $option = $this->getAttributeSource('options');
        if (empty($option)) {
            return [];
        }
        return is_array($option) ? $option : Json::decode($option);
    }

    public function setOptionsAttribute($value) {
        if (empty($value)) {
            $value = [];
        }
        if (is_array($value)) {
            $value = Json::encode($value);
        }
        $this->__attributes['options'] = $value;
    }

    public function saveOption(array $data) {
        $options = $this->options;
        $items = [];
        foreach ((array)$options as $item) {
            if (empty($item) || !is_array($item)) {
                continue;
            }
            $items[$item['code']] = $item;
        }
        foreach ($data as $item) {
            if (empty($item) || !is_array($item)) {
                continue;
            }
            if ($item['code'] === 'theme') {
                $this->theme = $item['value'];
            }
            if (isset($items[$item['code']])) {
                $items[$item['code']] = array_merge($items[$item['code']], $item);
                continue;
            }
            $items[$item['code']] = $item;
        }
        $this->options = array_values($items);
        $this->save();
    }
}