<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\LotteryRepository;
use Module\Shop\Service\Api\Controller;

class LotteryController extends Controller {

    public function rules() {
        return [
            'play' => '@',
            '*' => '*'
        ];
    }

    public function detailAction(int $id) {
        try {
            return $this->render(LotteryRepository::get($id, true));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function playAction(int $id, int $amount = 1) {
        try {
            return $this->render(LotteryRepository::play($id, $amount));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $id) {
        return $this->renderPage(LotteryRepository::logList($id));
    }
}