<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Exception;
use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Option;
use Zodream\Html\Tree;
use Zodream\ThirdParty\API\Microsoft;


class OptionRepository {

    public static function getEditList(): array {
        /** @var OptionModel[]  $items */
        $items = OptionModel::query()->where('visibility', '>', 0)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'desc')->get();
        return (new Tree($items))->makeTree();
    }

    public static function getOpenList(): array {
        $data = [];
        /** @var OptionModel[]  $items */
        $items = OptionModel::query()->where('visibility', '>', 1)
            ->orderBy('position', 'desc')->get('code', 'value', 'type');
        foreach ($items as $item) {
            $data[$item->code] = Option::formatOption((string)$item->getAttributeSource('value'), $item->type);
        }
        return $data;
    }

    public static function saveNewOption(array $data) {
        if (empty($data) || !isset($data['name'])) {
            throw new Exception('名称不能为空');
        }
        if (empty($data['name'])) {
            throw new Exception('名称不能为空');
        }
        if (OptionModel::where('name', $data['name'])->count() > 0) {
            throw new Exception('名称已存在');
        }
        if ($data['type'] == 'group') {
            return OptionModel::create([
                'name' => $data['name'],
                'type' => 'group'
            ]);
        }
        if (empty($data['code']) || $data['parent_id'] < 1) {
            throw new Exception('别名不能为空');
        }
        if (OptionModel::where('code', $data['code'])->count() > 0) {
            throw new Exception('别名已存在');
        }
        return OptionModel::create($data);
    }

    public static function saveOption($data) {
        if (empty($data)) {
            return;
        }
        foreach ($data as $id => $value) {
            OptionModel::where('id', $id)->update(compact('value'));
        }
    }

    public static function update(int $id, array $data) {
        $model = OptionModel::findOrThrow($id);
        if ($model->type === 'group' && $data['type'] !== $model->type) {
            throw new  Exception('分组不能改成项');
        }
        $model->load($data);
        if (OptionModel::where('name', $model['name'])->where('id', '<>', $id)->count() > 0) {
            throw new Exception('名称重复');
        }
        if ($model->type !== 'group') {
            if (!$model->code || $model->parent_id < 1) {
                throw new Exception('别名不能为空');
            }
            if (OptionModel::where('code', $model->code)->where('id', '<>', $id)->count() > 0) {
                throw new Exception('别名已存在');
            }
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        OptionModel::where('id', $id)->delete();
        OptionModel::where('parent_id', $id)->delete();
    }

    public static function todayWallpager() {
        $cache = cache();
        $cacheKey = __FUNCTION__;
        $data = $cache->get($cacheKey);
        if ($data !== false) {
            return $data;
        }
        $end = strtotime(date('Y-m-d').' 23:59:59') + 10;
        $diff = max($end - time(), 4 * 3600);
        $api = new Microsoft();
        $data = $api->wallpager(1);
        if (empty($data)) {
            return [];
        }
        $cache->set($cacheKey, $data, $diff);
        return $data;
    }
}