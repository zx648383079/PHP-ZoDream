<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $tag_id
 * @property integer $site_id
 */
class SiteTagModel extends Model {
    protected $primaryKey = '';

    public static function tableName() {
        return 'search_site_tag';
    }

    protected function rules() {
        return [
            'tag_id' => 'required|int',
            'site_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'tag_id' => 'Tag Id',
            'site_id' => 'Site Id',
        ];
    }
}
