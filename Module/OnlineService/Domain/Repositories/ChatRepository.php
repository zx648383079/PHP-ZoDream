<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Repositories;

use Module\OnlineService\Domain\Models\CategoryWordModel;
use Module\OnlineService\Domain\Models\MessageModel;
use Module\OnlineService\Domain\Models\SessionLogModel;
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

    public static function send(int $sessionId, array $data, int $sendType = 0) {
        $session = SessionModel::find($sessionId);
        if (!$session) {
            return;
        }
        if ($sendType < 1) {
            SessionModel::where('id', $sessionId)
                ->update([
                    'updated_at' => time()
                ]);
        }
        if (!static::sendMessage($sessionId, $data, $sendType)) {
            return;
        }
        if ($sendType < 1 && $session->service_word > 0) {
            static::createMessage($sessionId,
                CategoryWordModel::where('id', $session->service_word)
                    ->value('content'), 0, 1, $session->service_id);
        }
        $service_id = auth()->id();
        if ($sendType > 0 && $session->service_id != $service_id) {
            $session->service_id = $service_id;
            $session->status = 1;
            $session->save();
            SessionLogModel::create([
                'user_id' => $service_id,
                'session_id' => $sessionId,
                'remark' => sprintf('客服 【%s】 开始接待', auth()->user()->name),
                'status' => $session->status,
            ]);
        }
    }

    public static function sendMessage(int $sessionId, array $data, int $sendType = 0) {
        if (!isset($data['type'])) {
            $data['type'] = 0;
        }
        if (!is_array($data['content'])) {
            return static::createMessage($sessionId, $data['content'],
                intval($data['type']), $sendType);
        }
        $success = false;
        foreach ($data['content'] as $item) {
            if (empty($item)) {
                continue;
            }
            if (static::createMessage($sessionId, $item, intval($data['type']), $sendType)) {
                $success = true;
            }
        }
        return $success;
    }

    public static function createMessage(
        int $sessionId, string $content,
        int $type = 0, int $sendType = 0, int $userId = -1) {
        if (empty($content)) {
            return false;
        }
        return MessageModel::create([
            'user_id' => $userId < 0 ? auth()->id() : $userId,
            'session_id' => $sessionId,
            'send_type' => $sendType,
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
            'name' => auth()->guest() ? '游客_'.time() : auth()->user()->name,
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