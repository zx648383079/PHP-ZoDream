<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $thumb
 * @property string $link
 * @property string $site_id
 * @property integer $score
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class PageModel extends Model {
    public static function tableName() {
        return 'search_page';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,30',
            'description' => 'string:0,255',
            'thumb' => 'string:0,255',
            'link' => 'required|string:0,255',
            'site_id' => 'string:0,255',
            'score' => 'int:0,127',
            'user_id' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'link' => 'Link',
            'site_id' => 'Site Id',
            'score' => 'Score',
            'user_id' => 'User Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function site() {
        return $this->hasOne(SiteModel::class, 'id', 'site_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function keywords() {
        return $this->belongsToMany(KeywordModel::class, PageKeywordModel::class,
            'page_id', 'word_id');
    }
}
