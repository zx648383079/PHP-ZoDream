<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Concerns\ExtraRule;
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\MicroBlog\Domain\Repositories\LogRepository;


/**
 * Class CommentModel
 * @property integer $id
 * @property string $content
 * @property string $extra_rule
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $micro_id
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $created_at
 */
class CommentModel extends Model {

    use ExtraRule;

    protected array $append = ['agree_type', 'reply_count'];

	public static function tableName() {
        return 'micro_comment';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'extra_rule' => '',
            'parent_id' => 'int',
            'user_id' => 'int',
            'micro_id' => 'required|int',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'extra_rule' => 'Extra Rule',
            'parent_id' => 'Parent Id',
            'user_id' => 'User Id',
            'micro_id' => 'Micro Id',
            'agree_count' => 'Agree',
            'disagree_count' => 'Disagree',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id')->with('user');
    }

    public function micro() {
	    return $this->hasOne(MicroBlogModel::class, 'id', 'micro_id');
    }

    public function getReplyCountAttribute() {
	    return static::where('parent_id', $this->id)->count();
    }

    public function getAgreeTypeAttribute() {
	    return LogRepository::commentAgreeType($this->id);
    }
}