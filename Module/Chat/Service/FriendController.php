<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Module\Chat\Domain\Repositories\FriendRepository;
use Module\Chat\Domain\Repositories\MessageRepository;

class FriendController extends Controller {

    public function indexAction() {
        return $this->renderData(
            FriendRepository::all()
        );
    }

    public function searchAction(string $keywords = '') {
        return $this->renderData(
            FriendRepository::search($keywords)
        );
    }

    public function agreeAction(int $user, int $group = 0) {
        FriendRepository::follow($user, $group);
        return $this->renderData(true);
    }

    public function applyAction(int $user, int $group = 0, string $remark = '') {
        FriendRepository::follow($user, $group, $remark);
        return $this->renderData(true);
    }

    public function applyLogAction() {
        return $this->renderPage(
            FriendRepository::applyLog()
        );
    }

    public function deleteAction(int $user) {
        FriendRepository::remove($user);
        return $this->renderData(true);
    }

    public function moveAction(int $user, int $group) {
        FriendRepository::move($user, $group);
        return $this->renderData(true);
    }
}