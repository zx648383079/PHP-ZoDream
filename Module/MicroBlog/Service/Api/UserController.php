<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Repositories\UserRepository;

class UserController extends Controller {

    public function indexAction(int $id) {
        try {
            return $this->render(UserRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function reportAction(int $id) {
        try {
            UserRepository::report($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}