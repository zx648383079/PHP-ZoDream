<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\Chat\Domain\Repositories\MessageRepository;

class MessageController extends Controller {

    public function indexAction(int $type, int $id, int $start_time = 0) {
        return $this->render(
            MessageRepository::getList($type, $id, $start_time)
        );
    }

    public function pingAction(int $time = 0, int $user = 0) {
        return $this->render(
            MessageRepository::ping($time, $user)
        );
    }

    public function sendTextAction(int $type, int $id, string $content) {
        try {
            return $this->renderData(
                MessageRepository::sendText($type, $id, $content)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function sendImageAction(int $type, int $id) {
        try {
            return $this->renderData(
                MessageRepository::sendImage($type, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function sendVideoAction(int $type, int $id) {
        try {
            return $this->renderData(
                MessageRepository::sendVideo($type, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function sendAudioAction(int $type, int $id) {
        try {
            return $this->renderData(
                MessageRepository::sendAudio($type, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function sendFileAction(int $type, int $id) {
        try {
            return $this->renderData(
                MessageRepository::sendFile($type, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function revokeAction(int $id) {
        try {
            MessageRepository::revoke($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}