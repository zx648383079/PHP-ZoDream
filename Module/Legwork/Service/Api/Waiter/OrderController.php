<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Waiter;

use Module\Legwork\Domain\Repositories\WaiterRepository;

class OrderController extends Controller {

    public function indexAction(int $status = 0) {
        return $this->renderPage(
            WaiterRepository::orderList($status)
        );
    }

    public function takingAction($id) {
        try {
            WaiterRepository::taking($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function takenAction($id) {
        try {
            WaiterRepository::taken($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function waitingAction() {
        return $this->renderPage(
            WaiterRepository::waitTakingList()
        );
    }
}