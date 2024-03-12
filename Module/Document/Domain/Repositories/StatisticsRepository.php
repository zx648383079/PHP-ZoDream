<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;


use Module\Document\Domain\Model\ProjectModel;

final class StatisticsRepository {

    public static function subtotal(): array {

        return [];
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '文档数量',
                'count' => ProjectModel::where('user_id', $user)->count(),
                'unit' => '个',
            ],
        ];
    }
}