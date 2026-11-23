<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\ModelFieldEntity;

final class LiveSiteContext extends BaseContext {

    public function fieldItems(int $model): array {
        $fieldItems = ModelFieldEntity::where('model_id', $model)
                ->orderBy('position', SORT_ASC)
                ->thenBy('is_system', SORT_DESC)
                ->thenBy('id', SORT_ASC)
                ->get();
        return array_map(function ($item) {
            return $item->toArray();
        }, $fieldItems);
    }
}