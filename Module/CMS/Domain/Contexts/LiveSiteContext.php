<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\ModelFieldEntity;

final class LiveSiteContext extends BaseContext {

    public function fieldItems(int $model): array {
        $fieldItems = ModelFieldEntity::where('model_id', $model)
                ->orderBy('position', 'asc')
                ->orderBy('is_system', 'desc')
                ->orderBy('id', 'asc')
                ->get();
        return array_map(function ($item) {
            return $item->toArray();
        }, $fieldItems);
    }
}