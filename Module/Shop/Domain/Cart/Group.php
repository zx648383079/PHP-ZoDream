<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;


use Module\Shop\Domain\Models\ShippingModel;
use Traversable;
use Zodream\Infrastructure\Contracts\ArrayAble;
use ArrayIterator;

class Group implements \IteratorAggregate, ArrayAble {

    /**
     * @var ICartItem[]
     */
    protected array $items = [];

    /**
     * @var ShippingModel
     */
    protected $shipping;

    protected string $name = '';

    public function __construct(ICartItem $item) {
        $this->name = $item->getGroupName();
        $this->add($item);
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public function can(ICartItem $item) {
        return $item->getGroupName() == $this->name;
    }

    public function add(ICartItem $item) {
        if (!$this->can($item)) {
            return false;
        }
        foreach ($this->items as $cart) {
            if ($cart->canMerge($item)) {
                $cart->mergeItem($item);
                return true;
            }
        }
        $this->items[] = $item;
        return true;
    }

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
     * 根据商品id获取
     * @param $goodsId
     * @param int $productId
     * @return ICartItem|null
     */
    public function getGoods($goodsId, int $productId = 0) {
        foreach ($this->items as $item) {
            if ($item->goodsId() == $goodsId && $item->productId() == $productId) {
                return $item;
            }
        }
        return null;
    }

    public function remove($id) {
        foreach ($this->items as $key => $item) {
            if ($item->getId() == $id) {
                $item->delete();
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
        return $total + $this->shippingFee();
    }

    public function count() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->amount();
        }
        return $total;
    }

    public function shippingFee() {
        return 0;
    }

    public function all() {
        return array_values($this->items);
    }
    public function isEmpty() {
        return empty($this->items);
    }

    public function save() {
        foreach ($this->items as $item) {
            $item->save();
        }
        return $this;
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->all());
    }

    public function toArray() {
        return [
            'name' => $this->name,
            'goods_list' => $this->all()
        ];
    }
}