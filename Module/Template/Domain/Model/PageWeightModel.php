<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

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
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageWeightModel extends Model {
    public static function tableName() {
        return 'page_weight';
    }

    public function weight() {
        return $this->hasOne(WeightModel::class, 'name', 'weight_name');
    }

}