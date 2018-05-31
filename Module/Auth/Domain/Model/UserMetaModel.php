<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;
use Zodream\Domain\Access\Auth;

/**
 * Class UserMetaModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $key
 * @property string $value
 */
class UserMetaModel extends Model {

    public static function tableName() {
        return 'user_meta';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'key' => 'required|string:0,100',
            'value' => 'required',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'key' => 'Key',
            'value' => 'Value',
        ];
    }

    public static function getArr($key) {
        $value = self::where('user_id', Auth::id())->where('key', $key)->value('value');
        return empty($value) ? [] : unserialize($value);
    }

    public static function updateArr($key, array $value) {
        self::record()->where('user_id', Auth::id())->where('key', $key)
            ->update([
                'value' => serialize($value)
            ]);
    }

    public static function insertArr($key, array $value) {
        static::create([
            'user_id' => Auth::id(),
            'key' => $key,
            'value' => serialize($value)
        ]);
    }
}