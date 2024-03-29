<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Module\Chat\Domain\Repositories\MessageRepository;

class MessageController extends Controller {

    public function indexAction(int $type, int $id, int $start_time = 0) {
        return $this->render(
            MessageRepository::getList($type, $id, $start_time)
        );
    }

    public function pingAction(int $time = 0, int $user = 0) {
        return $this->renderData(
            MessageRepository::ping($time, $user)
        );
    }

}