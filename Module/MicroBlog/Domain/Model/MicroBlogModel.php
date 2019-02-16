<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;


/**
* Class MicroBlogModel
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $recommend
 * @property string $source
 * @property integer $created_at
 * @property integer $updated_at
*/
class MicroBlogModel extends Model {
	public static function tableName() {
        return 'micro_blog';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'content' => 'required|string:0,140',
            'recommend' => 'int',
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
            'recommend' => 'Recommend',
            'source' => 'Source',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * 不允许频繁发布
     * @return bool
     * @throws \Exception
     */
    public static function canPublish() {
	    $time = static::where('user_id', auth()->id())->max('created_at');
	    return !$time || $time < time() - 300;
    }

    public static function isRecommended($id) {
        return LogModel::where([
                'user_id' => auth()->id(),
                'type' => LogModel::TYPE_MICRO_BLOG,
                'id_value' => $id,
                'action' => LogModel::ACTION_RECOMMEND
            ])->count() > 1;
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
        return !!LogModel::create([
            'type' => LogModel::TYPE_MICRO_BLOG,
            'action' => LogModel::ACTION_RECOMMEND,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }
}