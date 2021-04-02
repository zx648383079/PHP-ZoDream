<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'chat/chat' => sprintf('%s@%s', ChatController::class, 'indexAction'),
            'chat/user' => sprintf('%s@%s', UserController::class, 'indexAction'),
            'chat/friend' => sprintf('%s@%s', FriendController::class, 'indexAction'),
            'chat/group' => sprintf('%s@%s', GroupController::class, 'indexAction'),
        ]));
    }
}