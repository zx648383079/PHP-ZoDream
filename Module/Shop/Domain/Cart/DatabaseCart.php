<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Models\CartModel;

final class DatabaseCart extends Cart {

    private array $oldArr = [];

    public function load(): void {
        $items = auth()->guest() ? [] : CartModel::where('user_id', auth()->id())
            ->get();
        $this->setItems($items);
        foreach ($this->items as $item) {
            $this->oldArr[] = $item->getId();
        }
        parent::load();
    }

    public function save(): void {
        $updated = [];
        foreach ($this->items as $item) {
            if ($item->isUpdated()) {
                $this->saveItem($item);
            }
            $updated[] = $item->getId();
        }
        $del = [];
        foreach ($this->oldArr as $id) {
            if (!in_array($id, $updated)) {
                $del[] = $id;
            }
        }
        if (empty($del)) {
            return;
        }
        CartModel::whereIn('id', $del)->delete();
    }

    protected function saveItem(ICartItem $item) {
        $userId =  auth()->id();
        if ($item instanceof CartModel) {
            $item->user_id = $userId;
            $item->save();
            return;
        }
        $item->setData([
            'user_id' => $userId,
        ]);
        if (empty($item->getId())) {
            $model = CartModel::createOrThrow($item->getData());
            $item->setData($model->getData());
            return;
        }
        CartModel::where('id', $item->getId())
            ->update($item->getData());
    }
}