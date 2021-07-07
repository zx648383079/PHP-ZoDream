<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\BargainRepository;
use Module\Shop\Service\Api\Controller;

class BargainController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            BargainRepository::getList($keywords)
        );
    }

    public function detailAction(int $id, bool $full = false) {
        try {
            return $this->render(BargainRepository::get($id, $full));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $activity) {
        return $this->renderPage(
            BargainRepository::logList($activity)
        );
    }
}