<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Game\GameMaker\Domain\Entities\ProjectEntity;

final class ProjectRepository {


    public static function selfList(string $keywords = '') {
        return ProjectEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
    }

    public static function selfGet(int $id) {
        return ProjectEntity::findWithAuth($id);
    }

    public static function selfSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? self::selfGet($id) : new ProjectEntity();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function selfRemove(int $id) {

    }

    public static function isSelf(int $project): bool {
        return ProjectEntity::where('id', $project)
            ->where('user_id', auth()->id())->count() > 0;
    }

    public static function isSelfOrThrow(int $project) {
        if (self::isSelf($project)) {
            return;
        }
        throw new Exception('project is error');
    }

    public static function selfStatistics(int $project) {
        self::isSelfOrThrow($project);
        $character_today = 0;
        $character_count = 0;
        $view_today = 0;
        $view_count = 0;
        $billing_today = 0;
        $billing_count = 0;
        return compact('character_count', 'character_today', 'view_today',
            'view_count', 'billing_count', 'billing_today');
    }
}