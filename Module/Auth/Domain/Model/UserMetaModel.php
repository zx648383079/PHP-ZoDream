<?php
namespace Module\Auth\Domain\Model;

use Domain\Concerns\TableMeta;
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

    use TableMeta;

    protected static string $idKey = 'user_id';
    protected static array $defaultItems = [
        'address_id' => 0, // 默认收货地址
        'id_card' => '', // 身份证
    ];

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

    public static function getVal(string $key) {
        return self::where('user_id', auth()->id())->where('name', $key)->value('content');
    }

    public static function updateVal(string $key, mixed $value) {
        self::where('user_id', auth()->id())->where('name', $key)
            ->update([
                'content' => is_array($value) ? serialize($value) : $value
            ]);
    }

    public static function insertVal(string $key, mixed $value) {
        static::create([
            'user_id' => auth()->id(),
            'name' => $key,
            'content' => is_array($value) ? serialize($value) : $value
        ]);
    }

    public static function getArr(string $key): array {
        $value = static::getVal($key);
        return empty($value) ? [] : unserialize($value);
    }

    public static function updateArr(string $key, array $value) {
        static::updateVal($key, $value);
    }

    public static function insertArr(string $key, array $value) {
        static::insertVal($key, $value);
    }
}