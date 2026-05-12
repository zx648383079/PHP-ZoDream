<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Scene\SceneInterface;

interface SiteContextInterface {


    public function scene(): SceneInterface;

    public function fieldItems(int $model): array;
}