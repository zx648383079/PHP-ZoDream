<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Command;


/**
 * Class OptionModel
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $parent_id
 * @property string $type
 * @property integer $visibility
 * @property string $default_value
 * @property string $value
 * @property integer $position
*/
class OptionModel extends Model {
	public static function tableName() {
        return 'option';
    }

    protected function rules() {
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

    protected function labels() {
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
	    return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * FIND ALL TO ASSOC ARRAY
     * @param $code
     * @return string
     */
	public static function findCode($code) {
		return static::where('code', $code)->value('value');
	}
}