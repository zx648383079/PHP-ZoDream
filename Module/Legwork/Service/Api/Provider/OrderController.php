<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Provider;

use Module\Legwork\Domain\Repositories\ProviderRepository;

class OrderController extends Controller {

    public function indexAction(int $status = 0, int $id = 0, int $user_id = 0, int $waiter_id = 0) {
        return $this->renderPage(
            ProviderRepository::orderList($status, $id, $user_id, $waiter_id)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(ProviderRepository::order($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function assignAction(int $id, int $waiter_id) {
        try {
            return $this->render(ProviderRepository::assignOrder($id, $waiter_id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}