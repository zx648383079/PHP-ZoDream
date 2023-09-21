<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\ContentEntity;
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
class ContentModel extends ContentEntity {

    protected $extend_data = null;

    public static function getExtendTable(string $table): string {
        return sprintf('content_%s_%s', CMSRepository::siteId(), $table);
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }
}