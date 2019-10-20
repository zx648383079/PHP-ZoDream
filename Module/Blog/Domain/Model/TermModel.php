<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\TermEntity;


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
class TermModel extends TermEntity {

    public function getUrlAttribute() {
        return url('./', ['category' => $this->id]);
    }

    public function getThumbAttribute() {
	    $thumb = $this->getAttributeSource('thumb');
        return url()->asset(empty($thumb) ? '/assets/images/banner.jpg' : $thumb);
    }

    public function blog() {
	    return $this->hasMany(BlogModel::class, 'term_id', 'id');
    }

}