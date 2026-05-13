<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\SceneInterface;
use Zodream\Database\Contracts\SqlBuilder;

interface SiteContextInterface {

    public function id(): int;

    public function source(): SiteModel;

    /**
     * 是否有本地化关联
     */
    public function isLocale(): bool;

    public function tableId(): int;
    public function theme(): string;

    public function language(): string;

    public function options(array|null $data = null): array;

    public function isOwer(): bool;


    public function channelTableName(): string;
    public function logTableName(): string;
    public function articleTableName(): string;

    public function channelBuilder(): SqlBuilder;
    public function articleBuilder(): SqlBuilder;
    public function channelSave(CategoryEntity|array $model): CategoryEntity;

    public function scene(): SceneInterface;

    public function fieldItems(int $model): array;
}