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

    protected function rules() {
        return [
            'id' => 'required|int',
            'name' => 'required|string:3-100',
            'title' => 'required|string:3-200',
            'keywords' => 'string:3-255',
            'description' => 'string:3-255',
            'template' => 'string:3-255',
            'settings' => '',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'template' => 'Template',
            'settings' => 'Settings',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function weights() {
        return $this->hasMany(PageWeightModel::class, 'name', 'name');
    }
}