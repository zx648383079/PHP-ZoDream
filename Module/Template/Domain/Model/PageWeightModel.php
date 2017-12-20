<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\Request;

/**
 * Class PageWeightModel
 * @package Module\Template
 * @property integer $id
 * @property integer $page_id
 * @property string $weight_name 部件名
 * @property integer $parent_id
 * @property integer $position
 * @property string $title
 * @property string $content
 * @property string $settings
 * @property boolean $is_share 是否通用
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageWeightModel extends Model {

    public static function tableName() {
        return 'page_weight';
    }

    protected function rules() {
        return [
            'name' => 'required|string:3-100',
            'weight_name' => 'string:3-30',
            'parent_id' => 'int',
            'position' => 'int',
            'title' => 'string:3-200',
            'content' => '',
            'settings' => '',
            'is_share' => 'int:0-1',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'weight_name' => 'Weight Name',
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
        return $this->hasOne(WeightModel::class, 'name', 'weight_name');
    }

    public static function saveFromPost() {
        $weight = WeightModel::find(['name', Request::request('weight_name')]);
        $maps = ['id',
            'page_id', 'weight_name', 'parent_id',
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
        $args['weight_name'] = $weight->name;
        $model = static::findOrNew($args['id']);
        $model->set($args);
        $model->save();
        return $model;
    }
}