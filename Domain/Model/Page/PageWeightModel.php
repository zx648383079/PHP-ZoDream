<?php
namespace Domain\Model\Page;

use Domain\Model\Model;

/**
 * Class PageWeightModel
 * @package Domain\Model\Page
 * @property integer $id
 * @property integer $page_id
 * @property integer $weight_id
 * @property integer $parent_id
 * @property integer $position
 * @property string $title
 * @property string $content
 * @property string $setting
 * @property integer $create_at
 */
class PageWeightModel extends Model {
    public static function tableName() {
        return 'page_weight';
    }


}