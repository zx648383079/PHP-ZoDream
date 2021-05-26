<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Model\MessageHistoryModel;
use Module\WeChat\Domain\Repositories\LogRepository;

class LogController extends Controller {


    public function indexAction(bool $mark = false) {
        return $this->renderPage(
            LogRepository::getList($this->weChatId(), $mark)
        );
    }

    public function markAction(int $id) {
        LogRepository::mark($id);
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        LogRepository::remove($id);
        return $this->renderData(true);
    }
}