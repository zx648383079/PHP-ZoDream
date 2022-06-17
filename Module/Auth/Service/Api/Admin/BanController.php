<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\BanRepository;

class BanController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(BanRepository::getList($keywords));
    }

    public function addAction(int $user) {
        try {
            BanRepository::banUser($user);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        BanRepository::remove($id);
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