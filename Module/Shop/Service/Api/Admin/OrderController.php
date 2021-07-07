<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Cron\ExpiredOrder;
use Module\Shop\Domain\Repositories\Admin\OrderRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class OrderController extends Controller {

    public function indexAction(
        string $series_number = '',
        int $status = 0,
        string|int $log_id = '',
        string $keywords = '',
        string $start_at = '',
        string $end_at = '',
        string $user = '',
        string $conginee = '',
        string $tel = '',
        string $address = '',
    ) {
        return $this->renderPage(
            OrderRepository::getList($series_number, $status, $log_id, $keywords, $start_at, $end_at, $user, $conginee, $tel, $address)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(OrderRepository::get($id, true));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input, int $id, string $operate) {
        try {
            return $this->render(OrderRepository::operate($id, $operate, $input->all()));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            OrderRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function cronAction() {
        new ExpiredOrder();
        return $this->renderData(true);
    }

}