<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;


/**
 * Class UserMetaModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $content
 */
class UserMetaModel extends Model {

    public static function tableName() {
        return 'user_meta';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => 'required',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }

    public static function getArr($key) {
        $value = self::where('user_id', auth()->id())->where('name', $key)->value('content');
        return empty($value) ? [] : unserialize($value);
    }

    public static function updateArr($key, array $value) {
        self::where('user_id', auth()->id())->where('name', $key)
            ->update([
                'content' => serialize($value)
            ]);
    }

    public static function insertArr($key, array $value) {
        static::create([
            'user_id' => auth()->id(),
            'name' => $key,
            'content' => serialize($value)
        ]);
    }
}