<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\ModelFieldEntity;
use Module\CMS\Domain\Scene\SceneInterface;

final class LiveSiteContext implements SiteContextInterface {

    public function scene(): SceneInterface {
        return app(SceneInterface::class);
    }

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