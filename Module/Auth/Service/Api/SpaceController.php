<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\UserSpaceRepository;

class SpaceController extends Controller {

    public function indexAction(int $user) {
        try {
            return $this->render(UserSpaceRepository::get($user));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function followAction(int $user) {
        try {
            return $this->renderData(
                UserSpaceRepository::toggleFollow($user)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function markAction(int $user) {
        try {
            return $this->renderData(
                UserSpaceRepository::toggleMark($user)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function reportAction(int $user, string $reason) {
        try {
            UserSpaceRepository::report($user, $reason);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}