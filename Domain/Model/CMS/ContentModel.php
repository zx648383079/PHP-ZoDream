<?php
namespace Domain\Model\CMS;

use Zodream\Domain\Html\Page;
/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property integer $status
 * @property integer $hit_count
 * @property integer $update_at
 * @property integer $create_at
 */
class ContentModel extends BaseModel {
    public static function tableName() {
        return 'content_'.static::site();
    }

    /**
     * @param $words
     * @return Page
     */
    public static function search($words) {
        return static::findPage();
    }
}