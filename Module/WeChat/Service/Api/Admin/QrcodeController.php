<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\QrcodeRepository;

class QrcodeController extends Controller {


    public function indexAction(int $wid = 0, string $keywords = '') {
        try {
            return $this->renderPage(QrcodeRepository::manageList(
                $wid,
                $keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}