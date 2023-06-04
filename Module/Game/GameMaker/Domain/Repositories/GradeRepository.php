<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Game\GameMaker\Domain\Entities\RuleGradeEntity;

final class GradeRepository {

    public static function makerList(int $project, string $keywords = '') {
        ProjectRepository::isSelfOrThrow($project);
        return RuleGradeEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('project_id', $project)->orderBy('grade', 'asc')->page();
    }

    public static function makerGenerate(int $project, array $data) {
        ProjectRepository::isSelfOrThrow($project);
        set_time_limit(0);
        $begin = intval($data['begin']);
        $end = intval($data['end']);
        $exp = intval($data['begin_exp']);
        $stepType = isset($data['step_type']) ? intval($data['step_type']) : 0;
        $stepExp = isset($data['step_exp']) ? floatval($data['step_exp']) : 0;
        do {
            self::makerAdd($project, $begin, $exp);
            $begin ++;
            if ($stepType <= 0) {
                continue;
            }
            if ($stepType < 1) {
                $exp += $stepExp;
            } else {
                $exp += $exp * $stepExp / 100;
            }
        } while($begin <= $end);
    }

    public static function makerSave(array $data) {
        ProjectRepository::isSelfOrThrow($data['project_id']);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? RuleGradeEntity::where('project_id', $data['project_id'])
            ->where('id', $id)->first() : new RuleGradeEntity();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function makerRemove(int $project, int $id) {
        ProjectRepository::isSelfOrThrow($project);
        RuleGradeEntity::where('project_id', $project)
            ->where('id', $id)->delete();
    }

    private static function makerAdd(int $project, int $grade, float|int $exp) {
        $count = RuleGradeEntity::where('project_id', $project)
            ->where('grade', $grade)->count();
        if ($count > 0) {
            RuleGradeEntity::where('project_id', $project)
                ->where('grade', $grade)->update([
                    'exp' => $exp
                ]);
            return;
        }
        RuleGradeEntity::create([
            'project_id' => $project,
            'grade' => $grade,
            'exp' => $exp
        ]);
    }

}