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
 * @property integer $created_at
 */
class TermModel extends Model {
	public static function tableName() {
        return 'term';
    }

	protected function rules() {
		return [
            'name' => 'required|string:3-200',
            'keywords' => 'string:3-200',
            'description' => 'string:3-200',
            'created_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'description',
            'keywords' => 'keywords',
            'created_at' => 'created_at'
        ];
	}

    public function getUrlAttribute() {
        return Url::to('blog/home', ['category' => $this->id]);
    }

}