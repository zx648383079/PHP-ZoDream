<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Traversable;
use Zodream\Helpers\Json;
use IteratorAggregate;
use Zodream\Infrastructure\Contracts\JsonAble;
use Zodream\Infrastructure\Contracts\ArrayAble;
use ArrayIterator;

class Cart implements IteratorAggregate, JsonAble, ArrayAble {

    /**
     * @var ICartItem[]
     */
    protected array $items = [];

    protected bool $booted = false;

    public function __construct() {
        $this->load();
    }

    public function load(): void {
        $this->booted = true;
    }

    public function save(): void {

    }

    protected function createItem(int $goodsId, array|string $properties, int $amount): ICartItem {
        return CartRepository::formatCartItem($goodsId, $properties, $amount);
    }

    /**
     * @param ICartItem[] $items
     * @return $this
     */
    public function setItems(array $items) {
        $this->items = [];
        $goodsId = [];
        foreach ($items as $item) {
            $goodsId[] = $item->goodsId();
            $this->add($item);
        }
        CartRepository::cache()->getAutoSet($goodsId, 'goods', function (array $goodsId) {
            return GoodsSimpleModel::query()->whereIn('id', $goodsId)->get();
        });
        return $this;
    }

    public function add(ICartItem $item) {
        foreach ($this->items as $cart) {
            if ($cart->canMerge($item)) {
                $cart->mergeItem($item);
                return true;
            }
        }
        $this->items[] = $item;
        return $this;
    }

    public function tryAdd(int $goodsId, array $properties, int $amount): ICartItem {
        $attrId = implode(',', $properties);
        foreach ($this->items as $item) {
            if ($item->is($goodsId, $attrId)) {
                $item->updateAmount($item->amount() + $amount);
                return $item;
            }
        }
        $item = $this->createItem($goodsId, $attrId, $amount);
        $this->items[] = $item;
        return $item;
    }

    public function tryUpdate(int $goodsId, array $properties, int $amount): ?ICartItem {
        $attrId = implode(',', $properties);
        foreach ($this->items as $key => $item) {
            if (!$item->is($goodsId, $attrId)) {
                continue;
            }
            $item->updateAmount($amount);
            if ($item->amount() > 0) {
                return $item;
            }
            unset($this->items[$key]);
            return null;
        }
        $item = $this->createItem($goodsId, $attrId, $amount);
        $this->items[] = $item;
        return $item;
    }

    public function update($id, int $amount) {
        $this->get($id)->updateAmount($amount);
        return $this;
    }

    /**
     * 根据购物车id获取购物车项
     * @param int|callable $id
     * @return ICartItem|null
     */
    public function get($id) {
        foreach ($this->items as $item) {
            if (is_callable($id) && call_user_func($id, $item)) {
                return $item;
            }
            if (!is_callable($id) && $item->getId() == $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 根据商品id 获取购物车项
     * @param $goodsId
     * @param int $productId
     * @return ICartItem|null
     */
    public function getItem($goodsId, array $properties = []) {
        $attrId = implode(',', $properties);
        foreach ($this->items as $item) {
            if ($item->is($goodsId, $attrId)) {
                return $item;
            }
        }
        return null;
    }

    public function clear() {
        $this->items = [];
        return true;
    }

    public function remove($ids) {
        if (!is_array($ids)) {
            $ids = func_get_args();
        }
        foreach ($ids as $item) {
            $this->removeId(is_numeric($item) ? $item : $item->id);
        }
        return $this;
    }

    public function removeId($id) {
        foreach ($this->items as $key => $item) {
            if ($item->getId() == $id) {
                unset($this->items[$key]);
                return true;
            }
        }
        return false;
    }

    public function total() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->total();
        }
        return $total;
    }

    public function count() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->amount();
        }
        return $total;
    }

    public function all() {
        return array_values($this->items);
    }

    public function filter(callable $cb) {
        $data = [];
        foreach ($this->items as $item) {
            if ($cb($item) === true) {
                $data[] = $item;
            }
        }
        return $data;
//        $cart = clone $this;
//        return $cart->setGoods($data);
    }

    public function isEmpty() {
        return empty($this->items);
    }


    public function checkoutButton() {
        return [
            'action' => 'checkout',
            'text' => '去结算'
        ];
    }

    public function promotionCell(array $subtotal) {
        $free = 88;
        if ($subtotal['total'] >= $free) {
            return [];
        }
        return [
            [
                'popup_tip' => sprintf('还差%d元包邮', $free - $subtotal['total']),
                'link' => [
                    'text' => '去凑单',
                    'url' => '',
                ]
            ]
        ];
    }

    public function subtotal() {
        return [
            'total' => $this->total(),
            'total_weight' => 1,
            'original_total' => $this->total(),
            'discount_amount' => 0,
            'count' => $this->count()
        ];
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->all());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array {
        $subtotal = $this->subtotal();
        return [
            'data' => $this->toGroupArray(),
            'subtotal' => $subtotal,
            'checkout_button' => $this->checkoutButton(),
            'promotion_cell' => $this->promotionCell($subtotal)
        ];
    }

    public function toGroupArray(): array {
        if (empty($this->items)) {
            return [];
        }
        $goodsId = [];
        foreach ($this->items as $item) {
            $goodsId[] = $item->goodsId();
        }
        $items = CartRepository::cache()->getAutoSet($goodsId, 'goods', function (array $goodsId) {
            return GoodsSimpleModel::query()->whereIn('id', $goodsId)->get();
        });
        $goodsItems = [];
        foreach ($items as $item) {
            $goodsItems[$item['id']] = $item;
        }
        $items = [];
        foreach ($this->items as $item) {
            $groupIndex = $item->getGroupName();
            if (!isset($items[$groupIndex])) {
                $items[$groupIndex] = [
                    'name' => $item->getGroupName(),
                    'goods_list' => []
                ];
            }
            $data = $item->getData();
            $data['goods'] = $goodsItems[$item->goodsId()];
            $items[$groupIndex]['goods_list'][] = $data;
        }
        return array_values($items);
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson(int $options = 0): string {
        return Json::encode($this->toArray());
    }
}