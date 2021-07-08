<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\BargainRepository;
use Module\Shop\Domain\Repositories\Activity\TrialRepository;
use Module\Shop\Service\Api\Controller;

class BargainController extends Controller {
    public function rules() {
        return [
            'apply' => '@',
            'cut' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            BargainRepository::getList($keywords)
        );
    }

    public function detailAction(int $id, string $log) {
        try {
            return $this->render(BargainRepository::getWithLog($id, $log));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $activity) {
        return $this->renderPage(
            BargainRepository::logList($activity)
        );
    }

    public function cutLogAction(int $activity, string $log) {
        return $this->renderPage(
            BargainRepository::cutLogList($activity, $log)
        );
    }

    public function applyAction(int $id) {
        try {
            return $this->render(BargainRepository::apply($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function cutAction(int $id, string $log) {
        try {
            return $this->render(BargainRepository::cut($id, $log));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }


}