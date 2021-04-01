<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\Chat\Domain\Repositories\GroupRepository;

class GroupController extends Controller {

    public function indexAction() {
        return $this->renderData(
            GroupRepository::all()
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                GroupRepository::detail($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function searchAction(string $keywords = '') {
        return $this->renderPage(
            GroupRepository::search($keywords)
        );
    }

    public function agreeAction(int $user, int $group) {
        GroupRepository::agree($user, $group);
        return $this->renderData(true);
    }

    public function applyAction(int $group, string $remark = '') {
        GroupRepository::apply($group, $remark);
        return $this->renderData(true);
    }

    public function applyLogAction(int $group) {
        return $this->renderPage(
            GroupRepository::applyLog($group)
        );
    }
}