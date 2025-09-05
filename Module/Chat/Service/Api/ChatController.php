<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\Chat\Domain\Repositories\ChatRepository;

class ChatController extends Controller {
    public function indexAction() {
        return $this->render(
            ChatRepository::histories()
        );
    }

    public function deleteAction(int $id) {
        try {
            ChatRepository::removeIdHistory($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}