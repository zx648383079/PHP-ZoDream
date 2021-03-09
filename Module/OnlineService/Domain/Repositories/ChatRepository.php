<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Repositories;

use Module\OnlineService\Domain\Models\MessageModel;
use Module\OnlineService\Domain\Models\SessionModel;

class ChatRepository {

    public static function getList(int $sessionId, int $startTime) {
        if (empty($startTime)) {
            $data = MessageModel::with('user')->where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')->limit(10)->get();
            $data = array_reverse($data);
        } else {
            $data = MessageModel::with('user')->where('session_id', $sessionId)
                ->where('created_at', '>=', $startTime)
                ->orderBy('created_at', 'asc')
                ->get();
        }
        foreach ($data as $item) {
            if (!$item->user) {
                $item->user = [
                    'name' => '游客',
                    'avatar' => url()->asset('assets/images/avatar/0.png')
                ];
            }
        }
        return $data;
    }

    public static function send(int $sessionId, array $data) {
        if (!isset($data['type'])) {
            $data['type'] = 0;
        }
        if (!is_array($data['content'])) {
            static::sendMessage($sessionId, $data['content'], intval($data['type']));
            return;
        }
        foreach ($data['content'] as $item) {
            if (empty($item)) {
                continue;
            }
            static::sendMessage($sessionId, $item, intval($data['type']));
        }
    }

    public static function sendMessage(int $sessionId, string $content, int $type = 0) {
        if (empty($content)) {
            return;
        }
        MessageModel::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'send_type' => 0,
            'type' => $type,
            'content' => $content,
        ]);
    }

    public static function sessionId(string $session_token = ''): int {
        $id = intval($session_token);
        if (!empty($id)) {
            return $id;
        }
        if (!auth()->guest()) {
            $id = SessionModel::where('user_id', auth()->id())
                ->max('id');
            if (!empty($id)) {
                return $id;
            }
        }
        $model = SessionModel::create([
            'user_id' => auth()->id(),
            'service_id' => 0,
            'ip' => request()->ip(),
            'user_agent' => request()->server('HTTP_USER_AGENT', '-'),
        ]);
        return $model->id;
    }

    public static function encodeSession(int $sessionId): string {
        return $sessionId.'';
    }

}