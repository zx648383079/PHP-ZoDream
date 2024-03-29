<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
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
    const STATUS_NONE = 0;
    const STATUS_ALLOW = 1;
    const STATUS_DISALLOW = 2;

    public static function tableName(): string {
        return 'leg_service';
    }

    protected function rules(): array {
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

    protected function labels(): array {
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

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function provider() {
        return $this->hasOne(ProviderModel::class, 'user_id', 'user_id');
    }

    public function getFormAttribute() {
        $setting = $this->getAttributeSource('form');
        return empty($setting) ? [] : Json::decode($setting);
    }

    public function setFormAttribute($value) {
        if (is_array($value)) {
            $items = [];
            foreach ($value as $item) {
                if (empty($item['name'])) {
                    continue;
                }
                if (empty($item['label'])) {
                    $item['label'] = $item['name'];
                }
                $items[] = $item;
            }
            $value = Json::encode($items);
        }
        $this->setAttributeSource('form', $value);
    }
}