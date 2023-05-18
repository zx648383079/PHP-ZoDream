<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Merchant;

use Module\Shop\Domain\Repositories\Admin\OrderRepository;
use Module\Shop\Service\Api\Admin\Controller;

class StatisticsController extends Controller {

    public function indexAction() {
        $subtotal = OrderRepository::getSubtotal();
        return $this->renderData($subtotal);
    }

}