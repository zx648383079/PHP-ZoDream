<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Waiter;

use Module\Legwork\Domain\Repositories\WaiterRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function indexAction() {
        try {
            return $this->render(
                WaiterRepository::getSelf()
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,100',
                'tel' => 'required|string:0,30',
                'address' => 'required|string:0,255',
                'longitude' => 'string:0,50',
                'latitude' => 'string:0,50',
            ]);
            return $this->render(
                WaiterRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}