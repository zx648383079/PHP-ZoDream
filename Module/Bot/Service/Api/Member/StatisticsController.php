<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        try {
            return $this->render(StatisticsRepository::subtotal($this->weChatId()));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}