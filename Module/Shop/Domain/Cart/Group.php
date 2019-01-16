<?php
namespace Module\Shop\Domain\Cart;


use Module\Shop\Domain\Model\ShippingModel;
use Zodream\Infrastructure\Interfaces\ArrayAble;
use ArrayIterator;

class Group implements \IteratorAggregate, ArrayAble {

    /**
     * @var ICartItem[]
     */
    protected $items = [];

    /**
     * @var ShippingModel
     */
    protected $shipping;

    protected $name = '';

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
            if ($item->getId() == $id) {
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
        return $this->items;
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

    public function getIterator() {
        return new ArrayIterator($this->toArray());
    }

    public function toArray() {
        return $this->all();
    }
}