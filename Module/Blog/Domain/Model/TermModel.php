<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;


/**
 * Class TermModel
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $thumb
 */
class TermModel extends Model {
	public static function tableName() {
        return 'blog_term';
    }

	protected function rules() {
        return [
            'name' => 'required|string:1,200',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'user_id' => 'int',
            'parent_id' => 'int',
            'thumb' => ''
        ];
	}

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '分类',
            'keywords' => '关键字',
            'description' => '说明',
            'user_id' => 'User Id',
            'parent_id' => '上级',
            'thumb' => '图片'
        ];
    }

    public function getUrlAttribute() {
        return url('./', ['category' => $this->id]);
    }

    public function getThumbAttribute() {
	    $thumb = $this->getAttributeSource('thumb');
        return empty($thumb) ? '/assets/images/banner.jpg' : $thumb;
    }

    public function blog() {
	    return $this->hasMany(BlogModel::class, 'term_id', 'id');
    }

}