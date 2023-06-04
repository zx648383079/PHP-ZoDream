<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Game\GameMaker\Domain\Entities\CharacterEntity;

final class CharacterRepository {


    public static function makerList(int $project, string $keywords = '') {
        ProjectRepository::isSelfOrThrow($project);
        return CharacterEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('id', 'desc')->page();
    }

}