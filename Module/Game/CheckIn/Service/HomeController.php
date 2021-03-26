<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Service;

use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;

class HomeController extends Controller {

    public function indexAction() {
        $model = CheckinRepository::today();
        return $this->show(compact('model'));
    }

    public function checkInAction() {
        try {
            return $this->renderData(
                CheckinRepository::check()
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}