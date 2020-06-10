<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Arr;

/**
 * Class PlatformOptionModel
 * @package Module\OpenPlatform\Domain\Model
 * @property integer $id
 * @property integer $platform_id
 * @property string $store
 * @property string $name
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class PlatformOptionModel extends Model {
    public static function tableName() {
        return 'open_platform_option';
    }

    protected function rules() {
        return [
            'platform_id' => 'required|int',
            'store' => 'required|string:0,20',
            'name' => 'required|string:0,30',
            'value' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'platform_id' => 'Platform Id',
            'store' => 'Store',
            'name' => 'Name',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 获取一个平台的配置
     * @param $platform_id
     * @param $store
     * @param array $default
     * @return array
     */
    public static function options($platform_id, $store, $default = []) {
        $data = static::where('platform_id', $platform_id)
            ->where('store', $store)
            ->pluck('value', 'name');
        return empty($data) ? $default : $data;
    }

    /**
     * 获取指定的配置
     * @param int $platform_id
     * @param array|string $store
     * @param array $default
     * @return array
     */
    public static function getStores($platform_id, $store, $default = []) {
        if (!is_array($store)) {
            return static::options($platform_id, $store, $default);
        }
        if (Arr::isAssoc($store) && empty($default)) {
            list($store, $default) = [array_keys($store), $store];
        }
        $data = static::query()->where('platform_id', $platform_id)
            ->whereIn('store', $store)->asArray()->get('store', 'name', 'value');
        $items = [];
        foreach ($store as $key) {
            $items[$key] = isset($default[$key]) && is_array($default[$key]) ? $default[$key] : [];
        }
        foreach ($data as $item) {
            $items[$item['store']][$item['name']] = $item['value'];
        }
        return $items;
    }

    /**
     * 删除一个平台的配置
     * @param $platform_id
     * @param $store
     * @return mixed
     * @throws \Exception
     */
    public static function deleteOption($platform_id, $store) {
        return static::query()->where('platform_id', $platform_id)
            ->where('store', $store)->delete();
    }

    /**
     * 保存配置
     * @param $platform_id
     * @param $store
     * @param array $options
     */
    public static function saveOption($platform_id, $store, $options = []) {
        $options = is_array($store) ? $store : [
            $store => $options
        ];
        $items = static::query()->where('platform_id', $platform_id)
            ->whereIn('store', array_keys($options))->asArray()->get('id', 'store', 'name');
        $add = [];
        $exist = [];
        foreach ($items as $item) {
            $exist[$item['store']][$item['name']] = $item['id'];
        }
        $now = time();
        foreach ($options as $store => $args) {
            foreach ($args as $name => $value) {
                if (isset($exist[$store]) && isset($exist[$store][$name])) {
                    static::query()->where('id', $exist[$store][$name])
                        ->update([
                            'value' => $value,
                            'updated_at' => $now
                        ]);
                    continue;
                }
                $add[] = [
                    'platform_id' => $platform_id,
                    'store' => $store,
                    'name' => $name,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        if (empty($add)) {
            return;
        }
        static::query()->insert($add);
    }
}