<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Module\Document\Domain\MockRule;

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

    public $type_list = [
        'string' => '字符串(string)',
        'json'   => '字符串(json)',
        'number' => '数字(number)',
        'float'  => '浮点型(float)',
        'boolean'=> '布尔型(boolean)',
        'array'  => '数组(array)',
        'object' => '对象(object)',
        'null'   => '对象(null)',
    ];

    public static function tableName() {
        return 'doc_field';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'title' => 'string:0,50',
            'is_required' => 'int:0,9',
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
            'name' => 'Name',
            'title' => 'Title',
            'is_required' => 'Is Required',
            'default_value' => 'Default Value',
            'mock' => 'Mock',
            'parent_id' => 'Parent Id',
            'api_id' => 'Api Id',
            'kind' => 'Kind',
            'type' => 'Type',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function setMock() {
        if ($this->kind != self::KIND_RESPONSE) {
            return $this;
        }
        $this->default_value = $this->getMockValueAttribute();
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
        $rule = $data[1] ? $data[1] : '';
        $value = $data[2] ? $data[2] : '';
        $mock = new MockRule();
        if(!method_exists($mock, $type)){
            return $this->mock;
        }
        return $mock->$type($rule, $value);
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
            $data[$name] = $v['default_value'];
        }
        return $data;

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
            $data[$name] = $v->getMockValueAttribute();
        }
        return $data;

    }
}