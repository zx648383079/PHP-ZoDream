<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Waiter;

use Module\Legwork\Domain\Repositories\WaiterRepository;

class ServiceController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, bool $all = false) {
        return $this->renderPage(
            WaiterRepository::serviceList($keywords, $category, $all)
        );
    }

    public function applyAction(int|array $id) {
        try {
            WaiterRepository::applyService($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}