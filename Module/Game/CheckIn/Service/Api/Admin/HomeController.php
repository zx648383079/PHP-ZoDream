<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Service\Api\Admin;

use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render(
            CheckinRepository::statistics()
        );
    }

    public function logAction(string $keywords = '', string $date = '') {
        return $this->render(
            CheckinRepository::logList($keywords, $date)
        );
    }

    public function optionAction() {
        return $this->render(
            CheckinRepository::option()
        );
    }

    public function optionSaveAction(int $basic = 1, int $loop = 0, array $plus = []) {
        try {
            CheckinRepository::optionSave($basic, $loop, $plus);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}