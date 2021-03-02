<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\MessageModel;

class MessageRepository {

    public static function ping(int $time = 0, int $user = 0) {
        $message = MessageModel::where('receive_id', auth()->id())
            ->when(!empty($time), function ($query) use ($time) {
                $query->where('created_at', '>=', intval($time));
            })
            ->where('status', MessageModel::STATUS_NONE)->count();
        $apply = ApplyModel::where('user_id', auth()->id())
            ->when(!empty($time), function ($query) use ($time) {
                $query->where('created_at', '>', intval($time));
            })->where('status', 0)->count();
        $messages = [];
        if ($user > 0) {
            $messages = MessageModel::with('user', 'receive')
                ->where('receive_id', auth()->id())
                ->where('user_id', $user)->where('created_at', '>=', intval($time))->get();
        }
        $time = time();
        return [
            'message_count' => $message,
            'apply_count' => $apply,
            'messages' => [
                $user => $messages,
            ],
            'time' => $time
        ];
    }
}