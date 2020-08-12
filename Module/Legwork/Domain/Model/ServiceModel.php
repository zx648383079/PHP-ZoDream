<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ServiceModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $cat_id
 * @property string $thumb
 * @property string $brief
 * @property float $price
 * @property string $content
 * @property string $form
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServiceModel extends Model {

    public static function tableName() {
        return 'leg_service';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'cat_id' => 'int',
            'thumb' => 'string:0,200',
            'brief' => 'string:0,255',
            'price' => '',
            'content' => 'required',
            'form' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '服务名',
            'cat_id' => '服务分类',
            'thumb' => '缩略图',
            'brief' => '说明',
            'price' => '单价',
            'content' => '内容',
            'form' => '表单',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function getFormAttribute() {
        $setting = $this->getAttributeValue('form');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setFormAttribute($value) {
        $this->__attributes['form'] = is_array($value) ?
            Json::encode($value) : $value;
    }
}