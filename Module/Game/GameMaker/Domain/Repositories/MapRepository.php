<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Game\GameMaker\Domain\Entities\MapAreaEntity;
use Module\Game\GameMaker\Domain\Entities\MapEntity;

final class MapRepository {

    public static function makerList(int $project) {
        ProjectRepository::isSelfOrThrow($project);
        $area_items = MapAreaEntity::where('project_id', $project)->get();
        $items = MapEntity::where('project_id', $project)->get();
        return compact('items', 'area_items');
    }

    public static function makerSave(array $data) {
        ProjectRepository::isSelfOrThrow($data['project_id']);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? MapEntity::where('project_id', $data['project_id'])
            ->where('id', $id)->first() : new MapEntity();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function makerRemove(int $project, int $id) {
        ProjectRepository::isSelfOrThrow($project);
        MapEntity::where('project_id', $project)
            ->where('id', $id)->delete();
    }

    public static function makerBatchSave(int $project, array $data) {
        ProjectRepository::isSelfOrThrow($project);
        foreach ($data as $item) {
            $item['project_id'] = $project;
            try {
                self::makerSave($item);
            } catch (Exception) {}
        }
    }
}