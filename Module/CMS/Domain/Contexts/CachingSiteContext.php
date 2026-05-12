<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\FuncHelper;

final class CachingSiteContext implements SiteContextInterface {

    public function scene(): SceneInterface {
        return app(SceneInterface::class);
    }

    public function fieldItems(int $model): array {
        return FuncHelper::fieldList($model);
    }
}