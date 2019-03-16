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

    public static function getVal($key) {
        return self::where('user_id', auth()->id())->where('name', $key)->value('content');
    }

    public static function updateVal($key, $value) {
        self::where('user_id', auth()->id())->where('name', $key)
            ->update([
                'content' => is_array($value) ? serialize($value) : $value
            ]);
    }

    public static function insertVal($key, $value) {
        static::create([
            'user_id' => auth()->id(),
            'name' => $key,
            'content' => is_array($value) ? serialize($value) : $value
        ]);
    }

    public static function getArr($key) {
        $value = static::getVal($key);
        return empty($value) ? [] : unserialize($value);
    }

    public static function updateArr($key, array $value) {
        static::updateVal($key, $value);
    }

    public static function insertArr($key, array $value) {
        static::insertVal($key, $value);
    }
}