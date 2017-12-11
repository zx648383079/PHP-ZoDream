<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

/**
 * Class PageModel
 * @package Module\Template
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $template
 * @property string $settings
 * @property integer $deleted_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class PageModel extends Model {
    public static function tableName() {
        return 'page';
    }

    public function weights() {
        return $this->hasMany(PageWeightModel::class, 'page_id', 'id');
    }
}