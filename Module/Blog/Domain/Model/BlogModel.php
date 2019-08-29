<?php
namespace Module\Blog\Domain\Model;

use Infrastructure\HtmlExpand;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Blog\Domain\Entities\BlogEntity;
use Zodream\Helpers\Time;


/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
 * @property string $language
 * @property string $programming_language
 * @property integer $parent_id
 * @property integer $edit_type
 * @property string $content
 * @property integer $user_id
 * @property integer $term_id
 * @property integer $type
 * @property string $source_url
 * @property integer $recommend
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $comment_status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
*/
class BlogModel extends BlogEntity {

	public function term() {
	    return $this->hasOne(TermModel::class, 'id', 'term_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function comment() {
	    return $this->hasMany(CommentModel::class, 'blog_id', 'id');
    }

	public function getUrlAttribute() {
	    return url('./', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return static::where('id', '<', $this->id)->orderBy('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)->orderBy('id asc')->select('id, title, description, created_at')->one();
    }

    /**
     * @return string|void
     */
    public function getCreatedAtAttribute() {
        return Time::isTimeAgo($this->getAttributeValue('created_at'), 2678400);
    }

    public function toHtml() {
        return cache()->getOrSet(sprintf('blog_%d_content', $this->id), function () {
            return TagModel::replaceTag($this->id, HtmlExpand::toHtml($this->content, $this->edit_type == 1));
        }, 3600);
    }

    public static function getNew($limit = 5) {
	    return static::orderBy('created_at desc')->select('id, title, description, created_at')->limit($limit ?? 5)->all();
    }

    public static function getHot() {
        return static::orderBy('comment_count desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getBest() {
        return static::orderBy('recommend desc')
            ->select('id, title, description, created_at')
            ->limit(5)->all();
    }

    public function getHotComment() {
	    return CommentModel::find()
            ->where(['blog_id' => $this->id])
            ->orderBy('created_at desc')->limit(5)->all();
    }

    /**
     * 是否允许评论
     * @param $id
     * @return bool
     */
    public static function canComment($id) {
        return static::where('comment_status', 1)
                ->where('id', $id)->count() > 0;
    }

    /**
     * 是否能推荐
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function canRecommend($id) {
        return BlogLogModel::where([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $id,
                'action' => BlogLogModel::ACTION_RECOMMEND
            ])->count() < 1;
    }

    /**
     * 推荐
     * @return bool
     * @throws \Exception
     */
    public function recommendThis() {
        $this->recommend++;
        if (!$this->save()) {
            return false;
        }
        return !!BlogLogModel::create([
            'type' => BlogLogModel::TYPE_BLOG,
            'action' => BlogLogModel::ACTION_RECOMMEND,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }

    public static function getOrNew($id, $language = null) {
        if (empty($language)) {
            return static::findOrNew($id);
        }
        if (!in_array($language, ['zh', 'en'])) {
            $language = 'zh';
        }
        if ($language == 'zh') {
            return static::findOrNew($id);
        }
        $model = static::where('parent_id', $id)->where('language', $language)->first();
        if (!empty($model)) {
            return $model;
        }
        $model = static::find($id);
        if (empty($model)) {
            return null;
        }
        $model->language = $language;
        $model->parent_id = $id;
        $model->id = 0;
        $model->content = '';
        return $model;
    }
}