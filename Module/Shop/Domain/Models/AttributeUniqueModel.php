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
class AttributeUniqueModel extends AttributeEntity {

    protected $append = ['attr_item'];

    /**
     * 补录默认选中的唯一属性
     * @param GoodsModel $goods
     * @param array $data
     * @throws \Exception
     */
    public static function batchSave(GoodsModel $goods, array $data) {
        if (empty($data)) {
            return;
        }
        $ids = static::where('group_id', $goods->attribute_group_id)
            ->where('type', 0)->pluck('id');
        if (empty($ids)) {
            return;
        }
        $items = [];
        foreach ($data as $attr_id => $attr_items) {
            if (!in_array($attr_id, $ids)) {
                continue;
            }
            if (!isset($attr_items[0])) {
                continue;
            }
            $items[] = [
                'goods_id' => $goods->id,
                'attribute_id' => $attr_id,
                'value' => $attr_items[0]['value'],
            ];
        }
        if (empty($items)) {
            return;
        }
        GoodsAttributeModel::query()->insert($items);
    }
}