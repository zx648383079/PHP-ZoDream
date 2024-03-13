<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\LogRepository;

class LogController extends Controller {


    public function indexAction(int $bot_id = 0, bool $mark = false) {
        try {
            return $this->renderPage(
                LogRepository::manageList($bot_id, $mark)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}