<?php
namespace Domain\Model\Shopping;

use Domain\Model\Model;

/**
 * 品牌
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property  string $name
 * @property string $logo
 * @property string $description
 * @property string $url
 * @property integer $create_at
 */
class BrandModel extends Model {
    public static function tableName() {
        return 'brand';
    }
}