<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin;

use Module\Shop\Domain\Repositories\Activity\ActivityRepository;

abstract class BaseActivity {

    protected array $data = [];

    abstract protected function ready(): void;

    public function has(int $goodsId): bool {
        return ActivityRepository::canUseGoods($this->data, $goodsId, false);
    }
}
