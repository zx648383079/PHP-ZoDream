<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api;

use Module\Catering\Domain\Repositories\OrderRepository;

class OrderController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction(int $store = 0) {
        return $this->renderPage(OrderRepository::getList($store));
	}
}