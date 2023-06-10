<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Entities\AttributeEntity;
use Module\Shop\Domain\Entities\AttributeGroupEntity;
use Module\Shop\Domain\Entities\GoodsAttributeEntity;
use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Entities\ProductEntity;
use Module\Shop\Domain\Models\AttributeModel;

final class AttributeRepository {

    public static function getList(int $group, string $keywords = '') {
        return AttributeModel::with('group')->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->where('group_id', $group)->orderBy('id', 'desc')
            ->page();
    }

    public static function get(int $id) {
        return AttributeEntity::findOrThrow($id);
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AttributeEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        AttributeEntity::where('id', $id)->delete();
        GoodsAttributeEntity::where('attribute_id', $id)->delete();
    }

    public static function groupList(string $keywords = '') {
        return AttributeGroupEntity::query()->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->orderBy('id', 'desc')
            ->page();
    }

    public static function groupGet(int $id) {
        return AttributeGroupEntity::findOrThrow($id);
    }

    public static function groupSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AttributeGroupEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function groupRemove(int $id) {
        AttributeGroupEntity::where('id', $id)->delete();
        $attrId = AttributeEntity::where('group_id', $id)->pluck('id');
        if (!empty($attrId)) {
            AttributeEntity::where('group_id', $id)->delete();
            GoodsAttributeEntity::whereIn('attribute_id', $attrId)->delete();
        }
        $goodsId = GoodsEntity::where('attribute_group_id', $id)->pluck('id');
        if (!empty($goodsId)) {
            GoodsEntity::where('attribute_group_id', $id)
                ->update([
                    'attribute_group_id' => 0,
                ]);
            ProductEntity::whereIn('goods_id', $goodsId)->delete();
        }
    }

    public static function groupAll() {
        $items = AttributeGroupEntity::query()->get('id', 'name', 'property_groups');
        return array_map(function ($item) {
            $groups = [];
            foreach (explode("\n", $item['property_groups']) as $group) {
                $group = trim($group);
                if (empty($group)) {
                    continue;
                }
                $groups[] = $group;
            }
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'property_groups' => $groups
            ];
        }, $items);
    }
}