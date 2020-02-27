<?php
namespace Module\Code\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class CodeCommentModel
 * @property integer $id
 * @property string $content
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $code_id
 * @property integer $agree
 * @property integer $disagree
 * @property integer $created_at
 */
class CommentModel extends Model {

    protected $append = ['is_agree', 'reply_count'];

	public static function tableName() {
        return 'code_comment';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'parent_id' => 'int',
            'user_id' => 'int',
            'code_id' => 'required|int',
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
            'code_id' => 'Micro Id',
            'agree' => 'Agree',
            'disagree' => 'Disagree',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id');
    }

    public function code() {
	    return $this->hasOne(CodeModel::class, 'id', 'code_id');
    }

    public function getReplyCountAttribute() {
	    return static::where('parent_id', $this->id)->count();
    }

    public function getAgreeTypeAttribute() {
	    $log = LogModel::where([
            'user_id' => auth()->id(),
            'type' => LogModel::TYPE_COMMENT,
            'id_value' => $this->id,
            'action' => ['in', [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE]]
        ])->first('action');
	    return !$log ? 0 : $log->action;
    }
}