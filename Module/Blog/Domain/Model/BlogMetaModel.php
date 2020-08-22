<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\BlogMetaEntity;

/**
 * Class BlogMetaModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property integer $blog_id
 * @property string $name
 * @property string $content
 */
class BlogMetaModel extends BlogMetaEntity {

}