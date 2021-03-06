<?php
namespace Module\Note\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Support\Html;

/**
 * Class NoteModel
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $created_at
 */
class NoteModel extends Model {


	public static function tableName() {
        return 'note';
    }

	protected function rules() {
		return [
            'content' => 'string:0,255',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'content' => 'Content',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
	}

	public function user() {
	    return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function getHtmlAttribute() {
	    $args = explode("\n", str_replace(["\t", ' '], ['    ', '&nbsp;'], strip_tags($this->content)));
	    return implode('', array_map(function ($item) {
	        return Html::p($item);
        }, $args));
    }

    public function getDateAttribute() {
        return Time::isTimeAgo($this->getAttributeSource('created_at'), 30 * 86400);
    }

    public static function getNew($limit) {
        return static::orderBy('created_at desc')->limit($limit ?? 5)->all();
    }

}