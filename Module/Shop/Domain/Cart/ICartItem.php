<?php
namespace Module\Shop\Domain\Cart;

interface ICartItem {

    public function getGroupName();

    public function getId();

    public function canMerge(ICartItem $item);

    public function mergeItem(ICartItem $item);

    public function total();

    public function amount();

    public function updateAmount(int $amount);

    public function save();
}