<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Repositories\BlogRepository;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
*/
class BlogContentModel extends BlogModel {

    const SIMPLE_MODE = ['id', 'title', 'edit_type', 'content'];

    public function getContentAttribute() {
        return BlogRepository::renderContent($this);
    }

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}