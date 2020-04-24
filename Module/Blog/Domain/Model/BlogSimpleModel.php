<?php
namespace Module\Blog\Domain\Model;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
*/
class BlogSimpleModel extends BlogModel {

    const SIMPLE_MODE = ['id', 'title', 'parent_id', 'description', 'created_at'];

    protected $append = ['url'];

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}