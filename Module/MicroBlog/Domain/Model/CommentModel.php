<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;


/**
 * Class CommentModel
 * @property integer $id
 * @property string $content
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $micro_id
 * @property integer $agree
 * @property integer $disagree
 * @property integer $created_at
 */
class CommentModel extends Model {

	public static function tableName() {
        return 'micro_comment';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'parent_id' => 'int',
            'user_id' => 'int',
            'micro_id' => 'required|int',
            'agree' => 'int',
            'disagree' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'parent_id' => 'Parent Id',
            'user_id' => 'User Id',
            'micro_id' => 'Micro Id',
            'agree' => 'Agree',
            'disagree' => 'Disagree',
            'created_at' => 'Created At',
        ];
    }

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id');
    }

    public function micro() {
	    return $this->hasOne(MicroBlogModel::class, 'id', 'micro_id');
    }

    public function getReplyCount() {
	    return $this->reply_count = static::where('parent_id', $this->id)->count();
    }

    public static function canAgree($id) {
	    return LogModel::where([
	        'user_id' => auth()->id(),
            'type' => LogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => ['in', [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE]]
        ])->count() < 1;
    }

    /**
     * 是否赞同此评论
     * @param bool $isAgree
     * @return bool
     * @throws \Exception
     */
    public function agreeThis($isAgree = true) {
        if ($isAgree) {
            $this->agree ++;
        } else {
            $this->disagree ++;
        }
        if (!$this->save()) {
            return false;
        }
        return !!LogModel::create([
            'type' => LogModel::TYPE_COMMENT,
            'action' => $isAgree ? LogModel::ACTION_AGREE : LogModel::ACTION_DISAGREE,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }
}