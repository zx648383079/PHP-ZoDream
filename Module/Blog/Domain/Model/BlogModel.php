<?php
namespace Module\Blog\Domain\Model;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Blog\Domain\Entities\BlogEntity;
use Module\Blog\Domain\Repositories\BlogRepository;


/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property integer $parent_id
 * @property string $programming_language
 * @property string $language
 * @property string $thumb
 * @property integer $edit_type
 * @property string $content
 * @property integer $user_id
 * @property integer $term_id
 * @property integer $type
 * @property integer $recommend_count
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $open_type
 * @property string $open_rule
 * @property integer $publish_status
 * @property integer $created_at
 * @property integer $updated_at
*/
class BlogModel extends BlogEntity {

    protected array $append = ['url', 'term', 'user', 'is_recommended'];

    protected array $hidden = ['open_rule'];

	public function term() {
	    return $this->hasOne(TermSimpleModel::class, 'id', 'term_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function comment() {
	    return $this->hasMany(CommentModel::class, 'blog_id', 'id');
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        return url()->asset(empty($thumb) ? '/assets/images/thumb.jpg' : $thumb);
    }

	public function getUrlAttribute() {
	    return url('./', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return BlogRepository::previous($this->id, $this->language);
    }

    public function getNextAttribute() {
        return BlogRepository::next($this->id, $this->language);
    }

    public function getIsRecommendedAttribute() {
	    return BlogRepository::hasLog($this->id, BlogLogModel::ACTION_RECOMMEND);
    }


    public function saveIgnoreUpdate() {
        return $this->save() || $this->isNotChangedError();
    }




}