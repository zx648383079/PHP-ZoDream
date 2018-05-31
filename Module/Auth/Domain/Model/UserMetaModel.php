<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;
use Zodream\Domain\Access\Auth;

class UserMetaModel extends Model {

    public static function tableName() {
        return 'user_meta';
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