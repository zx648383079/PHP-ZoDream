<?php
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Models\CartModel;
use Zodream\Helpers\Json;
use IteratorAggregate;
use Zodream\Infrastructure\Contracts\JsonAble;
use Zodream\Infrastructure\Contracts\ArrayAble;
use ArrayIterator;

class Cart implements IteratorAggregate, JsonAble, ArrayAble {

    const COOKIE_KEY = 'cart_identifier';
    /**
     * @var Group[]
     */
    protected $groups = [];

    protected $booted = false;

    public function __construct() {
        $this->loadFromDb();
    }

    public function id() {
        $id = request()->cookie(self::COOKIE_KEY);
        if (!empty($id)) {
            return $id;
        }
        $id = md5(uniqid(null, true));
        response()->cookie(self::COOKIE_KEY, $id, 0, '/');
        return $id;
    }

    protected function loadFromDb() {
        $this->booted = false;
        $this->setGoods(auth()->guest() ? [] : CartModel::with('goods')
            ->where('user_id', auth()->id())
            ->all());
        $this->booted = true;
    }

    /**
     * @param CartModel[] $goods
     * @return $this
     */
    public function setGoods(array $goods) {
        $this->groups = [];
        foreach ($goods as $item) {
            $this->add($item);
        }
        return $this;
    }

    public function add(ICartItem $item) {
        foreach ($this->groups as $group) {
            if ($group->can($item)) {
                $group->add($item);
                return $this;
            }
        }
        $this->groups[] = new Group($item);
        return $this;
    }

    public function update($id, $amount) {
        $this->get($id)->updateAmount($amount);
        return $this;
    }

    /**
     * 根据购物车id获取购物车项
     * @param int|callable $id
     * @return ICartItem|null
     */
    public function get($id) {
        foreach ($this->groups as $group) {
            if ($item = $group->get($id)) {
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
    public function getGoods($goodsId, $productId = 0) {
        foreach ($this->groups as $group) {
            if ($item = $group->getGoods($goodsId, $productId)) {
                return $item;
            }
        }
        return null;
    }

    public function clear() {
        foreach ($this->groups as $group) {
            foreach ($group as $item) {
                $item->delete();
            }
        }
        $this->groups = [];
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
        foreach ($this->groups as $key => $group) {
            if (!$group->remove($id)) {
                continue;
            }
            if ($group->isEmpty()) {
                unset($this->groups[$key]);
            }
            return true;
        }
        return false;
    }

    public function total() {
        $total = 0;
        foreach ($this->groups as $group) {
            $total += $group->total();
        }
        return $total;
    }

    public function count() {
        $total = 0;
        foreach ($this->groups as $group) {
            $total += $group->count();
        }
        return $total;
    }

    public function all() {
        return array_values($this->groups);
    }

    public function filter(callable $cb) {
        $data = [];
        foreach ($this->groups as $group) {
            foreach ($group as $item) {
                if ($cb($item) === true) {
                    $data[] = $item;
                }
            }
        }
        return $data;
//        $cart = clone $this;
//        return $cart->setGoods($data);
    }

    public function isEmpty() {
        return empty($this->groups);
    }

    public function save() {
        foreach ($this->groups as $group) {
            $group->save();
        }
        return $this;
    }

    public function checkoutButton() {
        return [
            'action' => 'checkout',
            'text' => '去结算'
        ];
    }

    public function promotionCell() {
        return [
            [
                'popup_tip' => '还差8元包邮',
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

    public function getIterator() {
        return new ArrayIterator($this->all());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        $items = array_map(function (Group $group) {
            return $group->toArray();
        }, $this->all());
        $subtotal = $this->subtotal();
        return [
            'data' => $items,
            'subtotal' => $subtotal,
            'checkout_button' => $this->checkoutButton(),
            'promotion_cell' => $subtotal['total'] > 99 ? [] : $this->promotionCell()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0) {
        return Json::encode($this->toArray());
    }
}