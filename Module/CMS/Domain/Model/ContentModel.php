<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Repositories\CMSRepository;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $title
 * @property integer $cat_id
 * @property integer $model_id
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property integer $status
 * @property integer $view_count
 * @property integer $created_at
 * @property integer $updated_at
 * @property CategoryModel $category
 */
class ContentModel extends BaseModel {

    protected $extend_data = null;

    public static function tableName() {
        return 'cms_content_'.CMSRepository::siteId();
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,100',
            'cat_id' => 'required|int',
            'model_id' => 'required|int',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'description' => 'string:0,255',
            'status' => 'int:0,9',
            'view_count' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'cat_id' => 'Category Id',
            'model_id' => 'Model Id',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'status' => 'Status',
            'view_count' => 'View Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }
}