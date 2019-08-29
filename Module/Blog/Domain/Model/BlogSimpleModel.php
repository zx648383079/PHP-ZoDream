<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\BlogEntity;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
*/
class BlogSimpleModel extends BlogEntity {

    const SIMPLE_MODE = ['id', 'title'];

	public function getUrlAttribute() {
	    return url('./', ['id' => $this->id]);
    }

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}