<?php
declare(strict_types=1);
namespace Module\OnlineService\Service\Api\Admin;

use Domain\Repositories\FileRepository;
use Module\OnlineService\Domain\Models\MessageModel;
use Module\OnlineService\Domain\Repositories\ChatRepository;
use Module\OnlineService\Domain\Repositories\SessionRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SessionController extends Controller {

    public function indexAction(string $keywords = '', int $status = 0) {
        return $this->renderPage(
            SessionRepository::getList($keywords, $status)
        );
    }

    public function myAction() {
        return $this->renderData(
            SessionRepository::myList()
        );
    }

    public function remarkAction(int $session_id, string $remark) {
        try {
            return $this->render(
                SessionRepository::remark($session_id, $remark)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function transferAction(int $session_id, int $user) {
        try {
            return $this->render(
                SessionRepository::transfer($session_id, $user)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function replyAction(int $session_id, int $word) {
        try {
            return $this->render(
                SessionRepository::reply($session_id, $word)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function messageAction(int $session_id, int $start_time = 0) {
        if (!SessionRepository::hasRole($session_id)) {
            return $this->renderFailure('此会话不属于你，无法查看');
        }
        return $this->render(
            $this->messageList($session_id, $start_time)
        );
    }

    public function sendAction(Input $input, int $session_id, int $start_time = 0) {
        if (!SessionRepository::hasRole($session_id)) {
            return $this->renderFailure('此会话不属于你，无法查看');
        }
        try {
            $file = $input->file('file');
            if (!empty($file)) {
                $items = FileRepository::uploadImages();
                $data['content'] = array_column($items, 'url');
                $data['type'] = $data['type'] = $input->get('type', MessageModel::TYPE_IMAGE);
            } else {
                $data = $input->validate([
                    'content' => 'required|string:0,255',
                    'type' => 'int:0,127',
                ]);
            }
            ChatRepository::send($session_id, $data, 1);
            return $this->render(
                $this->messageList($session_id, $start_time)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    protected function messageList(int $sessionId, int $startTime = 0) {
        $data = ChatRepository::getList($sessionId, $startTime);
        $next_time = time() + 1;
        return compact('data', 'next_time');
    }

}