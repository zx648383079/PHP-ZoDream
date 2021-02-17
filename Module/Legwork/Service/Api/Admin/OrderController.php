<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\OrderRepository;

class OrderController extends Controller {

    public function indexAction(string $keywords = '', int $status = 0, int $id = 0, int $user_id = 0,
                                int $service_id = 0,
                                int $provider_id = 0, int $waiter_id = 0) {
        return $this->renderPage(
            OrderRepository::getList($keywords, $status, $id, $user_id, $service_id, $provider_id, $waiter_id)
        );
    }
}