<?php
namespace Module\Blog\Domain\Model;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string weather
*/
class BlogContentModel extends BlogModel {

    const SIMPLE_MODE = ['id', 'title', 'edit_type', 'content', 'weather'];

    public function getContentAttribute() {
        return $this->toHtml();
    }

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}