<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\WaiterRepository;

class WaiterController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            WaiterRepository::getList($keywords)
        );
    }

    public function changeAction(int $id, int $status) {
        try {
            return $this->render(WaiterRepository::change($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}