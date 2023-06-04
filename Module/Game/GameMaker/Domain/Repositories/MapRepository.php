<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Game\GameMaker\Domain\Entities\IndigenousEntity;

final class MapRepository {

    public static function makerList(int $project) {
        ProjectRepository::isSelfOrThrow($project);
        return IndigenousEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('id', 'desc')->page();
    }

}