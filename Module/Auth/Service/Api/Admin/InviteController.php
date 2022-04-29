<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\InviteRepository;

class InviteController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(InviteRepository::codeList($keywords, $user));
    }

    public function saveAction(int $amount = 1, string $expired_at = '') {
        try {
            $model = InviteRepository::codeCreate($amount, $expired_at);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        InviteRepository::codeRemove($id);
        return $this->renderData(true);
    }

    public function clearAction() {
        InviteRepository::codeClear();
        return $this->renderData(true);
    }

    public function logAction(string $keywords = '', int $user = 0, int $inviter = 0) {
        return $this->renderPage(InviteRepository::logList($keywords, $user, $inviter));
    }
}