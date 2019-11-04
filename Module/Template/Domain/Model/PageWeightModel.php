<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;


/**
 * Class PageWeightModel
 * @package Module\Template
 * @property integer $id
 * @property integer $page_id
 * @property integer $theme_weight_id 部件名
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
 */
class PageWeightModel extends Model {

    public static function tableName() {
        return 'tpl_page_weight';
    }

    protected function rules() {
        return [
            'page_id' => 'required|int',
            'theme_weight_id' => 'required|int',
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
            'theme_weight_id' => 'Weight Id',
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
        return $this->hasOne(WeightModel::class, 'id', 'weight_id');
    }

    public function hasExtInfo($ext) {
        return false;
    }

    public static function saveFromPost() {
        $weight = WeightModel::find(intval(app('request')->get('weight_id')));
        $maps = ['id', 'page_id', 'weight_id', 'parent_id',
            'position', 'title', 'content', 'is_share', 'settings'];
        $data = $weight->getPostConfigs();
        $args = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $maps)) {
                $args[$key] = $value;
            } else {
                $args['settings'][] = $value;
            }
        }
        $args['weight_id'] = $weight->id;
        $model = static::findOrNew($args['id']);
        $model->set($args);
        $model->save();
        return $model;
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