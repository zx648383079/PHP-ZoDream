<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Database\Relation;
use Zodream\Html\Tree;

class LinkageRepository {
    public static function getList(string $keywords = '') {
        $items = LinkageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
        if ($items->isEmpty()) {
            return $items;
        }
        return ModelHelper::bindCount($items, LinkageDataModel::query(),
            'id', 'linkage_id');
    }

    public static function get(int $id) {
        return LinkageModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = LinkageModel::findOrNew($id);
        $model->load($data);
        if (static::exist($model)) {
            throw new \Exception('别名已存在');
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function exist(array|LinkageModel $data): bool {
        return LinkageModel::where('id', '<>', intval($data['id']))
            ->where('code', $data['code'])
            ->where('language', $data['language'] ?? '')->count() > 0;
    }

    public static function remove(int $id) {
        LinkageModel::where('id', $id)->delete();
        LinkageDataModel::where('linkage_id', $id)->delete();
    }


    public static function dataList(int $linkage, string $keywords = '', int $parent = 0) {
        $data = LinkageDataModel::query()
            ->where('linkage_id', $linkage)
            ->where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
        if ($data->isEmpty()) {
            return $data;
        }
        $idItems = Relation::columns($data, 'id');
        $items = LinkageDataModel::query()
            ->where('linkage_id', $linkage)
            ->whereIn('parent_id', $idItems)
            ->groupBy('parent_id')
            ->selectRaw('COUNT(*) as count,parent_id')
            ->pluck('count', 'parent_id');
        return $data->map(function ($item) use ($items) {
            $item['children_count'] = isset($items[$item['id']]) ? intval($items[$item['id']]) : 0;
            return $item;
        });
    }

    public static function dataSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = LinkageDataModel::findOrNew($id);
        if (LinkageDataModel::where('linkage_id', $model->linkage_id)
        ->where('parent_id', $model->parent_id)
        ->where('id', '<>', $id)->where('name', $model->name)->count() > 0) {
            throw new \Exception('名称已存在');
        }
        $model->load($data);
        $model->createFullName();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        CacheRepository::onLinkageUpdated(intval($model->linkage_id));
        return $model;
    }

    public static function dataRemove(int $id) {
        LinkageDataModel::where('id', $id)->delete();
    }

    public static function dataTree(string|int $id, string $lang = ''): array {
        if (is_numeric($id)) {
            $id = intval($id);
        } else {
            $id = intval(LinkageModel::where('language', $lang)->where('code', $id)->value('id'));
        }
        return FuncHelper::linkageData($id)->toIdTree();
    }
}