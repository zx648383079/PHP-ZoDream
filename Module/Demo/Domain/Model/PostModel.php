<?php
namespace Module\Demo\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class PostModel
 * @package Module\Demo\Domain\Model
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
 * @property string $content
 * @property string $file
 * @property integer $user_id
 * @property integer $cat_id
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $download_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class PostModel extends Model {
    public static function tableName() {
        return 'demo_post';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'content' => '',
            'file' => 'string:0,255',
            'user_id' => 'required|int',
            'cat_id' => 'required|int',
            'comment_count' => 'int',
            'click_count' => 'int',
            'download_count' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'content' => 'Content',
            'file' => 'File',
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'comment_count' => 'Comment Count',
            'click_count' => 'Click Count',
            'download_count' => 'Download Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        return url()->asset(empty($thumb) ? '/assets/images/banner.jpg' : $thumb);
    }


    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function saveIgnoreUpdate() {
        $isNew = $this->isNewRecord;
        $row = $this->save();
        if ($isNew) {
            return $row;
        }
        return $row || isset($this->errors['data']);
    }
}