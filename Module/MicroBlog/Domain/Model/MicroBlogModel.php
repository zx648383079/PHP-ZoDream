<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Model;

use Domain\Concerns\ExtraRule;
use Domain\Model\Model;
use Module\MicroBlog\Domain\LinkRule;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\MicroBlog\Domain\Repositories\LogRepository;


/**
* Class MicroBlogModel
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $extra_rule
 * @property integer $recommend_count
 * @property integer $forward_count
 * @property integer $comment_count
 * @property integer $collect_count
 * @property integer $forward_id
 * @property string $source
 * @property integer $created_at
 * @property integer $updated_at
*/
class MicroBlogModel extends Model {

    use ExtraRule;

    protected array $append = ['editable', 'is_recommended', 'attachment', 'is_collected'];

	public static function tableName() {
        return 'micro_blog';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'content' => 'required|string:0,140',
            'extra_rule' => '',
            'recommend_count' => 'int',
            'forward_count' => 'int',
            'comment_count' => 'int',
            'collect_count' => 'int',
            'forward_id' => 'int',
            'source' => 'string:0,30',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'content' => 'Content',
            'recommend_count' => 'Recommend Count',
            'forward_count' => 'Forward Count',
            'comment_count' => 'Comment Count',
            'collect_count' => 'Collect Count',
            'forward_id' => 'Forward Id',
            'source' => 'Source',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function forward() {
	    return $this->hasOne(static::class, 'id', 'forward_id');
    }

    public function attachment() {
	    return $this->hasMany(AttachmentModel::class, 'micro_id', 'id');
    }

    public function getIsRecommendedAttribute() {
	    return LogRepository::isRecommend($this->id);
    }

    public function getIsCollectedAttribute() {
	    return LogRepository::isCollect($this->id);
    }

    public function getEditableAttribute() {
        if (auth()->guest()) {
            return false;
        }
        return auth()->id() === $this->user_id;
    }

    public function getHtmlAttribute() {
        return LinkRule::render($this->content, $this->extra_rule);
    }
}