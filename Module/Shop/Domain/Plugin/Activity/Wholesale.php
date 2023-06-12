<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin\Activity;

use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Plugin\BaseActivity;
use Module\Shop\Domain\Plugin\IActivityPlugin;

class Wholesale extends BaseActivity implements IActivityPlugin {

    protected array $stepItems = [];
    protected function ready(): void {
        $this->stepItems = $this->data['configure']['items'];
    }
    public function getName(): string {
        return $this->data['name'];
    }

    public function getIntro(): string {
        return $this->data['description'];
    }

    /**
     * @param ICartItem $item
     * @return bool
     */
    public function isEnable(ICartItem $item): bool {
        return $this->has($item->goodsId());
    }

    /**
     * @param ICartItem[] $items
     * @return array
     */
    public function calculate(array $items): array {
        $data = [];
        foreach ($items as $item) {
            $res = $this->getPrice($item->amount());
            if ($res === false) {
                continue;
            }
            $item->updatePrice($res, intval($this->data['id']));
            $data[] = $item;
        }
        return $data;
    }

    protected function getPrice(int $amount): float|false {
        $price = false;
        $min = 0;
        foreach ($this->stepItems as $item) {
            if ($item['amount'] <= $amount && $min < $item['amount']) {
                $min = $item['amount'];
                $price = floatval($item['price']);
            }
        }
        return $price;
    }
}