<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\LogRepository;

class LogController extends Controller {


    public function indexAction(bool $mark = false) {
        try {
            return $this->renderPage(
                LogRepository::getList($this->weChatId(), $mark)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function markAction(int $id) {
        try {
            LogRepository::mark($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        try {
            LogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}