<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\QrcodeRepository;

class QrcodeController extends Controller {


    public function indexAction(int $bot_id = 0, string $keywords = '') {
        try {
            return $this->renderPage(QrcodeRepository::manageList(
                $bot_id,
                $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}