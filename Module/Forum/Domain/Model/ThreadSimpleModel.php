<?php
namespace Module\Forum\Domain\Model;


/**
* Class ThreadModel
 * @property integer $id
 * @property integer $forum_id
 * @property integer $classify_id
 * @property string $title
 * @property integer $user_id
 * @property integer $view_count
 * @property integer $post_count
 * @property integer $is_highlight
 * @property integer $is_digest
 * @property integer $is_closed
 * @property integer $created_at
 * @property integer $updated_at
*/
class ThreadSimpleModel extends ThreadModel {

    protected $append = ['url'];

    public function getUrlAttribute() {
	    return url('./thread', ['id' => $this->id]);
    }
}