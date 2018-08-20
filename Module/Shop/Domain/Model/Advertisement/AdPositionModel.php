<?php
namespace Module\Shop\Domain\Model\Advertisement;

use Domain\Model\Model;

/**
 * Class AdPositionModel
 * @package Domain\Model\Advertisement
 * @property integer $id
 * @property string $name
 * @property integer $width
 * @property integer $height
 * @property string $template
 * @property integer $update_at
 * @property integer $create_at
 */
class AdPositionModel extends Model {
    public static function tableName() {
        return 'shop_ad_position';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'width' => 'required|string:0,20',
            'height' => 'required|string:0,20',
            'template' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'width' => 'Width',
            'height' => 'Height',
            'template' => 'Template',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}