<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\OrderModel;

class Order extends OrderModel {

    protected $append = ['goods', 'status_label'];
}