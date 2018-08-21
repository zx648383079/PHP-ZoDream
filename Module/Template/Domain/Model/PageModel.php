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
        return 'tpl_page';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'title' => 'string:0,200',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'template' => 'string:0,255',
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
        return $this->hasMany(PageWeightModel::class, 'page_id', 'id');
    }
}