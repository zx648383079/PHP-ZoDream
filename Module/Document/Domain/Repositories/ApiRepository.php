<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;

use Exception;
use Module\Document\Domain\MockRule;
use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Xml;
use Zodream\Html\Tree;

class ApiRepository {

    public static function tree(int $project, int $version = 0) {
        $data = ApiModel::where('project_id', $project)
            ->where('version_id', $version)
            ->select('id', 'name', 'parent_id', 'type')
            ->orderBy('id', 'asc')->asArray()->get();
        return (new Tree($data))->makeTree();
    }

    public static function get(int $id) {
        return ApiModel::findOrThrow($id, '文档不存在');
    }

    public static function getSelf(int $id) {
        return static::get($id);
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new ApiModel();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function saveWeb(array $data) {
        $model = static::save($data);
        if (!isset($data['id']) || $data['id'] < 1) {
            FieldModel::whereIn('id', ApiModel::clearStore())->update([
                'api_id' => $model->id
            ]);
        }
        return $model;
    }

    public static function saveApi(array $data) {
        $model = static::save($data);
        $oldItems = [];
        if (isset($data['id']) && $data['id'] > 0) {
            $oldItems = FieldModel::where('api_id', $model->id)->get();
        }
        $doId = [];
        $findId = function ($data) use ($oldItems, $doId) {
            if (empty($oldItems)) {
                return 0;
            }
            if (isset($data['id']) && $data['id'] > 0) {
                return $data['id'];
            }
            foreach ($oldItems as $item) {
                if (in_array($item['id'], $doId)) {
                    continue;
                }
                $success = true;
                foreach ([
                    'name',
                    'parent_id',
                    'kind'
                         ] as $key) {
                    if ($item[$key] !== $data[$key]) {
                        $success = false;
                        break;
                    }
                }
                if ($success) {
                    return $item['id'];
                }
            }
            return 0;
        };
        $saveField = function (callable $cb, $kind, $parent_id, $item) use ($model, &$doId, $findId) {
            if (empty($item['name'])) {
                return;
            }
            $item['kind'] = $kind;
            $item['parent_id'] = $parent_id;
            $id = static::saveField($model->id, $findId($item), $parent_id, $item);
            if ($id < 1) {
                return;
            }
            $doId[] = $id;
            if (!isset($item['children']) || empty($item['children'])) {
                return;
            }
            foreach ($item['children'] as $arg) {
                $cb($kind, $id, $arg);
            }
        };
        foreach ([
            'header' => FieldModel::KIND_HEADER,
            'request' => FieldModel::KIND_REQUEST,
            'response' => FieldModel::KIND_RESPONSE
                 ] as $key => $kind) {
            if (!isset($data[$key]) || !is_array($data[$key])) {
                continue;
            }
            foreach ($data[$key] as $item) {
                $saveField($saveField, $kind, 0, $item);
            }
        }
        $del = [];
        foreach ($oldItems as $item)  {
            if (in_array($item['id'], $doId)) {
                continue;
            }
            $del[] = $item['id'];
        }
        if (!empty($del)) {
            FieldModel::whereIn('id', $del)->delete();
        }
        return $model;
    }

    protected static function saveField(int $apiID, int $id, int $parentId, array $data): int {
        unset($data['id']);
        if ($id > 0) {
            FieldModel::where('id', $id)
                ->update(array_merge(
                    $data,
                    [
                        'api_id' => $apiID,
                        'parent_id' => $parentId,
                        'updated_at' => time(),
                    ]
                ));
            return $id;
        } else {
            return FieldModel::query()->insert(array_merge(
                $data,
                [
                    'api_id' => $apiID,
                    'parent_id' => $parentId,
                    'updated_at' => time(),
                    'created_at' => time()
                ]
            ));
        }
    }

    public static function removeSelf(int $id) {
        $apiId = ApiModel::where('id', $id)->orWhere('parent_id', $id)->pluck('id');
        FieldModel::whereIn('api_id', $apiId)->delete();
        ApiModel::whereIn('id', $apiId)->delete();
    }

    public static function fieldList(int $apiId): array {
        $data = FieldModel::where('api_id', $apiId)->get();
        $items = [
            FieldModel::KIND_HEADER => [],
            FieldModel::KIND_REQUEST => [],
            FieldModel::KIND_RESPONSE => []
        ];
        foreach ($data as $item) {
            $items[$item->kind][] = $item;
        }
        if (!empty($items[FieldModel::KIND_RESPONSE])) {
            $items[FieldModel::KIND_RESPONSE] = (new Tree($items[FieldModel::KIND_RESPONSE]))
                ->makeTreeForHtml();
        }
        return array_values($items);
    }

    public static function fieldSelf(int $id) {
        return FieldModel::findOrThrow($id, '数据错误');
    }

    public static function fieldSave(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new FieldModel();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fieldRemove(int $id) {
        FieldModel::where('id', $id)->orWhere('parent_id', $id)->delete();
    }

    public static function canOpen(int $id): bool {
        $project_id = ApiModel::where('id', $id)
            ->value('project_id');
        return $project_id > 0 && ProjectRepository::canOpen($project_id);
    }

    public static function getRead(int|ApiModel $id): array {
        $model = $id instanceof ApiModel ? $id : static::get($id);
        list($header, $request, $response) = ApiRepository::fieldList($model->id);
        $example = MockRepository::getDefaultData($model->id);
        return array_merge(
            $model->toArray(),
            compact('header', 'request', 'response', 'example')
        );
    }
}