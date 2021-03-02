<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\Chat\Domain\Repositories\UserRepository;

class UserController extends Controller {
    public function indexAction() {
        return $this->render(UserRepository::profile());
    }

}