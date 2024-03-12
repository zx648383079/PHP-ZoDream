<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\AdminLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;

class LogController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AccountRepository::adminLog($keywords, $user)
        );
    }

    public function actionAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AccountRepository::actionLog($keywords, $user)
        );
    }

    public function loginAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AccountRepository::loginLog($keywords, $user)
        );
    }


}