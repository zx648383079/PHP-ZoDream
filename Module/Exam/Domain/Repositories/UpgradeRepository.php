<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Entities\UpgradeEntity;
use Module\Exam\Domain\Entities\UpgradePathEntity;
use Module\Exam\Domain\Entities\UpgradeUserEntity;

class UpgradeRepository {
    public static function getList(string $keywords = '', int $course = 0) {
        return UpgradeEntity::query()
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function get(int $id) {
        return UpgradeEntity::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = UpgradeEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        UpgradeEntity::where('id', $id)->delete();
        UpgradePathEntity::where('upgrade_id', $id)->delete();
        UpgradeUserEntity::where('upgrade_id', $id)->delete();
    }
}