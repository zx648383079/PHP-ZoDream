<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\FuncHelper;

final class CachingSiteContext extends BaseContext {

    public function fieldItems(int $model): array {
        return FuncHelper::fieldList($model);
    }
}