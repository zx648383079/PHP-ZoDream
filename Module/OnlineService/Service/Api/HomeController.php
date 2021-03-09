<?php
declare(strict_types=1);
namespace Module\OnlineService\Service\Api;

use Domain\Repositories\FileRepository;
use Module\OnlineService\Domain\Models\MessageModel;
use Module\OnlineService\Domain\Repositories\ChatRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function indexAction(string $session_token, int $start_time = 0) {
        $sessionId = ChatRepository::sessionId($session_token);
        return $this->render(
            $this->messageList($sessionId, $start_time)
        );
    }

    public function sendAction(Input $input, string $session_token = '', int $start_time = 0) {
        try {
            $file = $input->file('file');
            if (!empty($file)) {
                $items = FileRepository::uploadImages();
                $data['content'] = array_column($items, 'url');
                $data['type'] = MessageModel::TYPE_IMAGE;
            } else {
                $data = $input->validate([
                    'content' => 'required|string:0,255',
                    'type' => 'int:0,127',
                ]);
            }
            $sessionId = ChatRepository::sessionId($session_token);
            ChatRepository::send($sessionId, $data);
            return $this->render(
                $this->messageList($sessionId, $start_time)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    protected function messageList(int $sessionId, int $startTime = 0) {
        $data = ChatRepository::getList($sessionId, $startTime);
        $session_token = ChatRepository::encodeSession($sessionId);
        $next_time = time();
        return compact('data', 'session_token', 'next_time');
    }
}