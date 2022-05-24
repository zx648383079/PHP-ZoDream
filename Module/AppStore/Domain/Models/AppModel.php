<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 *
 * @property integer $id
 * @property integer $cat_id
 * @property integer $user_id
 * @property string $name
 * @property string $package_name
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $icon
 * @property integer $is_free
 * @property integer $is_open_source
 * @property string $official_website
 * @property string $git_url
 * @property integer $comment_count
 * @property integer $download_count
 * @property integer $view_count
 * @property float $score
 * @property integer $updated_at
 * @property integer $created_at
 */
class AppModel extends Model {

	public static function tableName() {
        return 'app_software';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'cat_id' => 'required|int',
            'name' => 'required|string:0,20',
            'package_name' => 'string:0,60',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'content' => '',
            'icon' => 'string:0,255',
            'is_free' => 'int:0,127',
            'is_open_source' => 'int:0,127',
            'official_website' => 'string:0,255',
            'git_url' => 'string:0,255',
            'comment_count' => 'int',
            'download_count' => 'int',
            'view_count' => 'int',
            'score' => 'numeric',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'content' => 'Content',
            'icon' => 'Icon',
            'is_free' => 'Is Free',
            'is_open_source' => 'Is Open Source',
            'official_website' => 'Official Website',
            'git_url' => 'Git Url',
            'comment_count' => 'Comment Count',
            'download_count' => 'Download Count',
            'view_count' => 'View Count',
            'score' => 'Score',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}