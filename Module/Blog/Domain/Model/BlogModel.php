<?php
namespace Module\Blog\Domain\Model;

use Infrastructure\HtmlExpand;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Blog\Domain\Entities\BlogEntity;
use Module\Blog\Domain\Repositories\BlogRepository;
use Zodream\Helpers\Time;


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
 * @property integer $recommend
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $open_type
 * @property string $open_rule
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
*/
class BlogModel extends BlogEntity {

    protected array $append = ['url', 'term', 'user', 'is_recommended', 'can_read'];

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
	    return static::where('id', '<', $this->id)
            ->where('language', $this->language)
            ->where('open_type', '<>', BlogModel::OPEN_DRAFT)->orderBy('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)
            ->where('language', $this->language)->where('open_type', '<>', BlogModel::OPEN_DRAFT)->orderBy('id asc')->select('id, title, description, created_at')->one();
    }

    public function getIsRecommendedAttribute() {
	    if (auth()->guest()) {
	        return false;
        }
	    return !self::canRecommend($this->id);
    }

    public function getCanReadAttribute() {
	    if ($this->open_type < 1 || $this->user_id == auth()->id()) {
	        return true;
        }
	    if ($this->open_type == self::OPEN_LOGIN) {
	        return !auth()->guest();
        }
        if ($this->open_type == self::OPEN_PASSWORD) {
            if (auth()->guest()) {
                return session('BLOG_PWD') === $this->open_rule;
            }
            return BlogLogModel::where([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $this->id,
                'action' => BlogLogModel::ACTION_REAL_RULE
            ])->count() > 1;
        }
        if ($this->open_type == self::OPEN_BUY) {
            if (auth()->guest()) {
                return false;
            }
            return BlogLogModel::where([
                    'user_id' => auth()->id(),
                    'type' => BlogLogModel::TYPE_BLOG,
                    'id_value' => $this->id,
                    'action' => BlogLogModel::ACTION_REAL_RULE
                ])->count() > 0;
        }
        return false;
    }

    /**
     * @return string|void
     */
    public function getCreatedAtAttribute() {
        return Time::isTimeAgo($this->getAttributeValue('created_at'), 2678400);
    }

    public function toHtml() {
        return BlogRepository::renderContent($this);
    }

    public function getHotComment() {
	    return CommentModel::query()
            ->where(['blog_id' => $this->id])
            ->orderBy('created_at desc')->limit(5)->all();
    }

    public function saveIgnoreUpdate() {
        $isNew = $this->isNewRecord;
        $row = $this->save();
        if ($isNew) {
            return $row;
        }
        return $row || isset($this->errors['data']);
    }

    /**
     * 是否允许评论
     * @param $id
     * @return bool
     */
    public static function canComment($id) {
        return BlogMetaModel::where('name', 'comment_status')
                ->where('content', 1)
                ->where('blog_id', $id)->count() > 0;
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