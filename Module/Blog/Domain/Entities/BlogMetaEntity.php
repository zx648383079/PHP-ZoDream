<?php
namespace Module\Blog\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class BlogMetaEntity
 * @package Module\Blog\Domain\Entities
 * @property integer $id
 * @property integer $blog_id
 * @property string $name
 * @property string $content
 */
class BlogMetaEntity extends Entity {
	public static function tableName() {
        return 'blog_meta';
    }

    protected function rules() {
        return [
            'blog_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => 'required',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'blog_id' => 'Blog Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }
}