<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Scene\SingleScene;

class ModelRepository {
    public static function getList(string $keywords = '', int $type = 0) {
        return ModelModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->page();
    }

    public static function get(int $id) {
        return ModelModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ModelModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ModelModel::where('id', $id)->delete();
        ModelFieldModel::where('model_id', $id)->delete();
    }

    public static function all(int $type = 0) {
        return ModelModel::query()
            ->where('type', $type)
            ->get('id', 'name');
    }

    public static function fieldList(int $model) {
        return ModelFieldModel::query()
            ->where('model_id', $model)->get();
    }

    public static function field(int $id) {
        return ModelFieldModel::findOrThrow($id, '数据有误');
    }

    public static function fieldSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ModelFieldModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fieldRemove(int $id) {
        ModelFieldModel::where('id', $id)->delete();
    }

    /**
     * 对属性进行分组
     * @param int $model
     * @return array
     */
    public static function fieldGroupByTab(int $model) {
        $tab_list = self::fieldTab($model);
        $data = [];
        foreach ($tab_list as $i => $item) {
            $data[$item] = [
                'name' => $item,
                'active' => $i < 1,
                'items' => []
            ];
        }
        $field_list = ModelFieldModel::where('model_id', $model)->orderBy([
            'position' => 'asc',
            'id' => 'asc'
        ])->get();
        foreach ($field_list as $item) {
            $name = $item->tab_name;
            if (empty($name) || !in_array($name, $tab_list)) {
                $name = $item->is_main > 0 ? $tab_list[0] : $tab_list[1];
            }
            $data[$name]['items'][] = $item;
        }
        return array_values($data);
    }

    /**
     * 获取所有的分组标签
     * @param int $model
     * @return string[]
     */
    public static function fieldTab(int $model) {
        $tab_list = ModelFieldModel::where('model_id', $model)->pluck('tab_name');
        $data = ['基本', '高级'];
        foreach ($tab_list as $item) {
            $item = trim($item);
            if (empty($item) || in_array($item, $data)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    public static function fieldType(): array {
        $items = (new ModelFieldModel())->type_list;
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $data;
    }

    public static function fieldOption(string $type, int $field) {
        $model = ModelFieldModel::findOrNew($field);
        $field = SingleScene::newField($type);
        $data = $field->options($model, true);
        return empty($data) || !is_array($data) ? [] : $data;
    }
}