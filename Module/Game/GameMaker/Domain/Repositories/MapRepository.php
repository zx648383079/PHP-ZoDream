<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Exception;
use Module\Game\GameMaker\Domain\Entities\MapAreaEntity;
use Module\Game\GameMaker\Domain\Entities\MapEntity;

final class MapRepository {

    const POS_MAP = ['north_id', 'east_id', 'south_id', 'west_id'];

    public static function makerList(int $project) {
        ProjectRepository::isSelfOrThrow($project);
        $area_items = MapAreaEntity::where('project_id', $project)->get();
        $items = MapEntity::where('project_id', $project)->get();
        return compact('items', 'area_items');
    }

    public static function makerSave(array $data) {
        ProjectRepository::isSelfOrThrow($data['project_id']);
        return self::doSave($data);
    }

    public static function doSave(array $data, bool $isBatch = false) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? MapEntity::where('project_id', $data['project_id'])
            ->where('id', $id)->first() : new MapEntity();
        $updateKey = [];
        foreach (self::POS_MAP as $key) {
            if ($id > 0) {
                if (isset($data[$key]) && $data[$key] !== $model[$key]) {
                    $updateKey[] = $key;
                }
            } else if (isset($data[$key]) && $data[$key] > 0) {
                $updateKey[] = $key;
            }
        }
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        foreach ($updateKey as $key) {
            $i = array_search($key, self::POS_MAP);
            $toKey = $i > 1 ? self::POS_MAP[$i - 2] : self::POS_MAP[$i + 2];
            $val = $model[$key];
            if ($val > 0) {
                MapEntity::where('project_id', $data['project_id'])
                    ->where('id', $val)
                    ->update([
                        $toKey => $model->id
                    ]);
            } else {
                MapEntity::where('project_id', $data['project_id'])
                    ->where($toKey, $model->id)
                    ->update([
                        $toKey => 0
                    ]);
            }
        }
        return $model;
    }

    public static function makerRemove(int $project, int $id) {
        ProjectRepository::isSelfOrThrow($project);
        MapEntity::where('project_id', $project)
            ->where('id', $id)->delete();
    }

    public static function makerAdd(array $data) {
        return self::makerSave($data);
    }

    public static function makerMove(int $project, array $items) {
        ProjectRepository::isSelfOrThrow($project);
        foreach ($items as $item) {
            if (!isset($item['id'])) {
                continue;
            }
            MapEntity::where('project_id', $project)
                ->where('id', intval($item['id']))
                ->update([
                    'x' => intval($item['x']),
                    'y' => intval($item['y'])
                ]);
        }
    }

    public static function makerLink(int $project, int $from, string $fromKey, int $to, string $toKey = '') {
        ProjectRepository::isSelfOrThrow($project);
        $i = array_search($fromKey, self::POS_MAP);
        if ($i === false) {
            throw new Exception('from key error');
        }
        if (empty($toKey)) {
            $toKey = $i > 1 ? self::POS_MAP[$i - 2] : self::POS_MAP[$i + 2];
        } elseif (!in_array($toKey, self::POS_MAP)) {
            throw new Exception('to key error');
        }
        MapEntity::where('project_id', $project)
            ->where('id', $from)
            ->update([
                $fromKey => $to,
            ]);
        MapEntity::where('project_id', $project)
            ->where('id', $to)
            ->update([
                $toKey => $from,
            ]);
    }

    public static function makerBatchSave(int $project, array $data) {
        ProjectRepository::isSelfOrThrow($project);
        foreach ($data as $item) {
            $item['project_id'] = $project;
            try {
                self::doSave($item, true);
            } catch (Exception) {}
        }
    }
}