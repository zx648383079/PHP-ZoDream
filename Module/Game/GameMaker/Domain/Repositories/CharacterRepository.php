<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterIdentityEntity;

final class CharacterRepository {

    public static function makerList(int $project, string $keywords = '') {
        ProjectRepository::isSelfOrThrow($project);
        return CharacterEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('id', 'desc')->page();
    }

    public static function makerIdentityList(int $project, string $keywords) {
        ProjectRepository::isSelfOrThrow($project);
        return CharacterIdentityEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('id', 'desc')->page();
    }

    public static function makerIdentitySave(array $data) {
        ProjectRepository::isSelfOrThrow($data['project_id']);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? CharacterIdentityEntity::where('project_id', $data['project_id'])
            ->where('id', $id)->first() : new CharacterIdentityEntity();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function makerIdentityRemove(int $project, int $id) {
        ProjectRepository::isSelfOrThrow($project);
        if (CharacterEntity::where('project_id', $project)
            ->where('identity_id', $id)->count() > 0) {
            CharacterIdentityEntity::where('project_id', $project)
                ->where('id', $id)->update([
                    'status' => 9,
                ]);
            return;
        }
        CharacterIdentityEntity::where('project_id', $project)
            ->where('id', $id)->delete();
    }

}