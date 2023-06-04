<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\LogRepository;

class LogController extends Controller {


    public function indexAction(int $wid = 0, bool $mark = false) {
        try {
            return $this->renderPage(
                LogRepository::manageList($wid, $mark)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}