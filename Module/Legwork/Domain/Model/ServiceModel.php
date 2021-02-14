<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class ServiceModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $cat_id
 * @property string $thumb
 * @property string $brief
 * @property float $price
 * @property string $content
 * @property string $form
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServiceModel extends Model {

    public static function tableName() {
        return 'leg_service';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'cat_id' => 'int',
            'thumb' => 'string:0,200',
            'brief' => 'string:0,255',
            'price' => '',
            'content' => 'required',
            'form' => '',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'cat_id' => 'Cat Id',
            'thumb' => 'Thumb',
            'brief' => 'Brief',
            'price' => 'Price',
            'content' => 'Content',
            'form' => 'Form',
            'status' => 'Status',
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