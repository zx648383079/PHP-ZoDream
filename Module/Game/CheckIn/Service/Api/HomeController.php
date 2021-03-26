<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Service\Api;

use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;

class HomeController extends Controller {

    public function rules() {
        return [
            'index' => '@',
        ];
    }

    public function indexAction() {
        return $this->renderData(
            CheckinRepository::today()
        );
    }

    public function checkInAction() {
        try {
            return $this->renderData(
                CheckinRepository::check(CheckinRepository::formatMethod())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function monthAction(string $month = '') {
        return $this->renderData(
            CheckinRepository::monthLog($month)
        );
    }

}