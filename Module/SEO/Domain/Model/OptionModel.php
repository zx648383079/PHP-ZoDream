<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Model;

use Domain\Model\Model;
use Module\SEO\Domain\Events\OptionUpdated;
use Zodream\Helpers\Json;

/**
 * Class OptionModel
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $parent_id
 * @property string $type
 * @property integer $visibility // 是否对外显示, 0 页面不可见，1 编辑可见 2 前台可见
 * @property string $default_value
 * @property string $value // type 为 'select', 'radio', 'checkbox' 时，值都为序号，方便进行多语言切换，或者 key:label 组合中的key
 * @property integer $position
 */
class OptionModel extends Model {

    public static function tableName(): string {
        return 'seo_option';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,20',
            'code' => 'string:0,20',
            'parent_id' => 'int',
            'type' => 'string:0,20',
            'visibility' => 'int:0,9',
            'default_value' => 'string:0,255',
            'value' => '',
            'position' => 'int:0,9999',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => '别名',
            'parent_id' => 'Parent Id',
            'type' => '类型',
            'visibility' => '公开',
            'default_value' => '默认值',
            'value' => '值',
            'position' => '排序',
        ];
    }

    public function children() {
        return $this->hasMany(static::class, 'parent_id', 'id')
            ->where('type', '!=', 'hide');
    }

    public function setAppend($name) {
        $this->append = (array)$name;
        return $this;
    }

    /**
     * FIND ALL TO ASSOC ARRAY
     * @param $code
     * @return string
     */
    public static function findCode(string $code): string {
        return static::where('code', $code)->value('value').'';
    }

    /**
     * 获取设置并解码
     * @param string $code
     * @param array $default
     * @return array
     */
    public static function findCodeJson(string $code, array $default = []) {
        $value = static::findCode($code);
        if (empty($value)) {
            return $default;
        }
        return Json::decode($value);
    }

    /**
     * 更新或插入设置
     * @param string $code
     * @param string $value
     * @param callable|string $name
     * @return bool
     * @throws \Exception
     */
    public static function insertOrUpdate(string $code, string $value, callable|string $name) {
        $id = static::where('code', $code)->value('id');
        if (!empty($id)) {
            static::where('id', $id)->update([
                'value' => $value,
            ]);
        } else {
            $data = is_callable($name) ? call_user_func($name) : [
                'name' => $name,
            ];
            $data = array_merge([
                'name' => $code,
                'code' => $code,
                'parent_id' => '0',
                'type' => 'hide',
                'visibility' => 0,
                'default_value' => '',
                'value' => $value,
            ], $data);
            static::query()->insert($data);
        }
        event(new OptionUpdated());
        return true;
    }

    /**
     * 添加一个分组
     * @param array|string $name
     * @param callable $cb
     * @return bool
     * @throws \Exception
     */
    public static function group(string|array $name, callable $cb) {
        if (!is_array($name)) {
            $name = compact('name');
        }
        $name['type'] = 'group';
        $name['parent_id'] = 0;
        $id = static::findOrNewByName($name);
        if ($id < 1) {
            return false;
        }
        $items = call_user_func($cb, $id);
        if (!is_array($items)) {
            return true;
        }
        $item = reset($items);
        if (!is_array($item)) {
            $items = [$items];
        }
        foreach ($items as $item) {
            $item['parent_id'] = $id;
            static::findOrNewByCode($item);
        }
        return true;
    }

    private static function findOrNewByName(array $data) {
        $data = array_merge([
            'type' => 'group',
            'parent_id' => 0,
            'visibility' => 1,
        ], $data);
        unset($data['id']);
        $id = static::where('name', $data['name'])
            ->where('type', $data['type'])
            ->where('parent_id', $data['parent_id'])->value('id');
        if (!empty($id)) {
            return $id;
        }
        return static::query()->insert($data);
    }

    private static function findOrNewByCode(array $data) {
        $data = array_merge([
            'parent_id' => '0',
            'type' => 'text',
            'visibility' => 1,
            'default_value' => '',
            'value' => '',
        ], $data);
        unset($data['id']);
        if (empty($data['code'])) {
            return 0;
        }
        $id = static::where('code', $data['code'])->value('id');
        if (!empty($id)) {
            static::where('id', $id)->update($data);
            return $id;
        }
        return static::query()->insert($data);
    }
}