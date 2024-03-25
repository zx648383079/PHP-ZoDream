<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;
use Module\Document\Domain\Repositories\MockRepository;
use Zodream\Helpers\Arr;

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

    public static function tableName(): string {
        return 'doc_field';
    }

    protected function rules(): array {
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

    protected function labels(): array {
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
        return static::$type_list[$this->type];
    }

    public function setMock() {
        if ($this->kind != self::KIND_RESPONSE) {
            return $this;
        }
        $val = $this->getMockValueAttribute().'';
        $this->default_value = MockRepository::format($this->type, $val);
        return $this;
    }

    public function getMockValueAttribute() {
       return MockRepository::mockValue($this->mock);
    }

    public function save(bool $force = false): mixed {
        parent::save($force);
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


    public function toArray(): array
    {
        $data = parent::toArray();
        if ($this->type === 'array' || $this->type === 'object') {
            $data['children'] = Arr::toArray($this->children);
        }
        return $data;
    }
}