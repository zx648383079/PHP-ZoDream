<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Routing\Url;

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
        return 'term';
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
            'name' => 'Name',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'user_id' => 'User Id',
            'parent_id' => 'Parent Id',
        ];
    }

    public function getUrlAttribute() {
        return Url::to('./home', ['category' => $this->id]);
    }

    public function blog() {
	    return $this->hasMany(BlogModel::class, 'term_id', 'id');
    }

}