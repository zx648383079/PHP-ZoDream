<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;


use Domain\Model\SearchModel;
use Exception;
use Module\Game\GameMaker\Domain\Entities\DescentEntity;

final class DescentRepository {

    public static function makerList(int $project, string $keywords = '') {
        ProjectRepository::isSelfOrThrow($project);
        return DescentEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('id', 'desc')->page();
    }

    public static function makerSave(array $data) {
        ProjectRepository::isSelfOrThrow($data['project_id']);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? DescentEntity::where('project_id', $data['project_id'])
            ->where('id', $id)->first() : new DescentEntity();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function makerRemove(int $project, int $id) {
        ProjectRepository::isSelfOrThrow($project);
        DescentEntity::where('project_id', $project)
            ->where('id', $id)->delete();
    }
}