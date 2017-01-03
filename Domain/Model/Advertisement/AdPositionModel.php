<?php
namespace Domain\Model\Advertisement;

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
        return 'ad_position';
    }

}