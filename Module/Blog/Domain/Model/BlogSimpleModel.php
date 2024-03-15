<?php
namespace Module\Blog\Domain\Model;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
*/
class BlogSimpleModel extends BlogModel {

    const SIMPLE_MODE = ['id', 'title', 'thumb', 'parent_id', 'language', 'description', 'created_at'];

    protected array $append = ['url'];

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}