<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\AttributeEntity;
use Zodream\Helpers\Json;

/**
 * Class AttributeModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property integer $type
 * @property integer $search_type
 * @property integer $input_type
 * @property string $default_value
 * @property integer $position
 */
class AttributeModel extends AttributeEntity {

    public static $search_list = ['不需要检索', '关键字检索', '范围检索'];
    public static $type_list = ['唯一属性', '单选属性', '复选属性'];

    protected array $append = ['group'];

    public function group() {
        return $this->hasOne(AttributeGroupModel::class, 'id', 'group_id');
    }

}