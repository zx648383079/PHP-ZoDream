<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Module\Document\Domain\MockRule;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Xml;

/**
 * Class FieldModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $is_required
 * @property string $default_value
 * @property string $mock
 * @property integer $parent_id
 * @property integer $api_id
 * @property integer $kind
 * @property string $type
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class FieldModel extends Model {

    const KIND_REQUEST = 1;
    const KIND_RESPONSE = 2;
    const KIND_HEADER = 3;

    public static $type_list = [
        'string' => '字符串(string)',
        'json'   => '字符串(json)',
        'number' => '数字(number)',
        'float'  => '浮点型(float)',
        'double' => '双精度浮点型(double)',
        'boolean'=> '布尔型(boolean)',
        'array'  => '数组(array)',
        'object' => '对象(object)',
        'null'   => '对象(null)',
    ];

    public $children = [];

    public static function tableName() {
        return 'doc_field';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'title' => 'string:0,50',
            'is_required' => 'bool',
            'default_value' => 'string:0,255',
            'mock' => 'string:0,255',
            'parent_id' => 'int',
            'api_id' => 'required|int',
            'kind' => 'int:0,999',
            'type' => 'string:0,10',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '字段名',
            'title' => '名称',
            'is_required' => '是否必须',
            'default_value' => '默认值',
            'mock' => 'Mock',
            'parent_id' => 'Parent Id',
            'api_id' => 'Api Id',
            'kind' => '类型',
            'type' => '值类型',
            'remark' => '备注',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getHasChildrenAttribute() {
        return in_array($this->type, ['array', 'object']);
    }

    public function getTypeLabelAttribute() {
        return $this->type_list[$this->type];
    }

    public function setMock() {
        if ($this->kind != self::KIND_RESPONSE) {
            return $this;
        }
        $val = $this->getMockValueAttribute().'';
        $this->default_value = self::format($this->type, $val);
        return $this;
    }

    public function getMockValueAttribute() {
        if (empty($this->mock)) {
            return null;
        }
        $data = explode('|', $this->mock);
        $type = $data[0];
        if (!$type){
            return $this->mock;
        }
        $rule = isset($data[1]) ? $data[1] : '';
        $value = isset($data[2]) ? $data[2] : '';
        $mock = new MockRule();
        if ($type == 'array') {
            $type = 'arr';
        }
        if(!method_exists($mock, $type)){
            return $this->mock;
        }
        return $mock->$type($rule, $value);
    }

    public function save() {
        parent::save();
        if ($this->id < 1) {
            return false;
        }
        if (empty($this->children)) {
            return true;
        }
        foreach ($this->children as $item) {
            $item->api_id = $this->api_id;
            $item->parent_id = $this->id;
            $item->kind = $this->kind;
            $item->save();
        }
        return true;
    }

    public function check(array $exits) {
        if (empty($exits)) {
            return true;
        }
        return self::where('kind', $this->kind)->where('api_id', $this->api_id)
            ->where('parent_id', intval($this->parent_id))->where('name', $this->name)
                ->whereIn('id', $exits)
                ->where('id', '<>', $this->id)->count() < 1;
    }

    /**
     * 获取响应字段默认值数组
     * @param int $api_id
     * @param int $parent_id
     * @return mixed
     */
    public static function getDefaultData($api_id, $parent_id = 0) {
        $fields = self::where('kind', self::KIND_RESPONSE)->where('api_id', $api_id)->where('parent_id', $parent_id)->all();
        $data = [];
        foreach ($fields as $k => $v){
            $name = $v['name'];
            if($v['type'] == 'array'){
                $data[$name][] = self::getDefaultData($api_id, $v['id']);
                continue;
            }
            if($v['type'] == 'object'){
                $data[$name] = self::getDefaultData($api_id, $v['id']);
                continue;
            }
            if ($v['default_value'] === '' && $v['mock']) {
                $v->setMock();
            }
            $data[$name] = self::format($v['type'], $v['default_value']);
        }
        return $data;

    }

    public static function format($type, $val) {
        if ($type === 'number') {
            return floatval($val);
        }
        if ($type === 'boolean') {
            if (is_bool($val)) {
                return $val;
            }
            return $val === 'true';
        }
        return $val;
    }

    /**
     * 获取响应字段mock数组
     * @param $api_id
     * @param int $parent_id
     * @return mixed
     */
    public static function getMockData($api_id, $parent_id = 0) {
        $api_id = $api_id ? $api_id : 0;
        $fields = self::where('kind', self::KIND_RESPONSE)->where('api_id', $api_id)->where('parent_id', $parent_id)->all();
        $data = [];
        foreach ($fields as $k => $v){
            $name = $v['name'];
            if ($v['type'] == 'array'){
                $value = self::getMockData($api_id, $v['id']);
                $data[$name][] = $value ? $value : array();
                continue;
            }
            if($v['type'] == 'object'){
                $value = self::getMockData($api_id, $v['id']);
                $data[$name] = $value ? $value : (object)array();
                continue;
            }
            $data[$name] = self::format($v->type, $v->getMockValueAttribute());
        }
        return $data;

    }

    /**
     * @param string $content
     * @param int $kind
     * @return static[]
     */
    public static function parseContent($content, $kind = self::KIND_REQUEST) {
        if ($kind == self::KIND_HEADER) {
            return self::parseHeader($content);
        }
        if (substr($content, 0, 1) == '{') {
            $args = Json::decode($content);
        } elseif (substr($content, 0, 1) == '<') {
            $args = Xml::specialDecode(preg_replace('/^[\s\S]*\<xml\>/', '<xml>', $content));
        } else {
            $args = [];
            parse_str($content, $args);
        }
        $data = [];
        foreach ($args as $key => $item) {
            $model = new static([
                'name' => $key,
                'title' => $key,
                'type' => 'string',
                'kind' => $kind,
                'default_value' => is_null($item) || is_array($item) || strlen($item) > 30 ? '' : $item,
                'is_required' => !empty($item) ? 1 : 0
            ]);
            self::parseChildren($item, $model);
            $data[] = $model;
        }
        return $data;
    }

    public static function parseChildren($content, FieldModel $model) {
        if (is_null($content)) {
            $model->type = 'null';
        }
        if (is_bool($content)) {
            $model->type = 'boolean';
            return;
        }
        if (is_float($content)) {
            $model->type = 'float';
            return;
        }
        if (is_double($content)) {
            $model->type = 'double';
            return;
        }
        if (is_numeric($content)) {
            $model->type = strpos($content, '.') === false ? 'number' : 'float';
            return;
        }
        if (!is_array($content)) {
            return;
        }
        $model->default_value = '';
        $model->type = Arr::isAssoc($content) ? 'object' : 'array';
        $data = $model->type == 'array' ? reset($content) : $content;
        foreach ($data as $key => $item) {
            $child = new static([
                'name' => $key,
                'title' => $key,
                'type' => 'string',
                'default_value' => is_null($item) || is_array($item) || strlen($item) > 30 ? '' : $item,
                'is_required' => !empty($item)
            ]);
            self::parseChildren($item, $child);
            $model->children[] = $child;
        }
    }

    public static function parseHeader($content) {
        $data = [];
        foreach (explode("\n", $content) as $line) {
            if (strpos($line, ':') === false) {
                continue;
            }
            list($key, $value) = explode(':', $line, 2);
            $key = trim($key);
            if (empty($key)) {
                continue;
            }
            $value = trim($value);
            $data[] = new static([
                'name' => $key,
                'title' => $key,
                'default_value' => $value,
                'type' => 'string',
                'kind' => self::KIND_HEADER,
                'is_required' => !empty($value)
            ]);
        }
        return $data;
    }
}