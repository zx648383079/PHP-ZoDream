<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\FriendModel;
use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\MessageModel;
use Zodream\Html\Page;

class ChatRepository {

    public static function histories() {
        /** @var Page $page */
        $page = ChatHistoryModel::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->page();
        $userIds = [];
        $groupIds = [];
        $messageIds = [];
        foreach ($page as $item) {
            if ($item['last_message'] > 0) {
                $messageIds[] = $item['last_message'];
            }
            if ($item['item_type'] < 1) {
                $userIds[] = $item['item_id'];
                continue;
            }
            $groupIds[] = $item['item_id'];
        }
        $users = static::getUsers($userIds);
        $friends = static::getFriend($userIds);
        $groups = static::getGroup($groupIds);
        $messages = static::getLastMessage($messageIds);
        foreach ($page as $item) {
            $item['message'] = isset($messages[$item['last_message']]) ? $messages[$item['last_message']] : null;
            if ($item['item_type'] < 1) {
                $item['user'] = isset($users[$item['item_id']]) ? $users[$item['item_id']] : null;
                $item['friend'] = isset($friends[$item['item_id']]) ? $friends[$item['item_id']] : null;
                continue;
            }
            $item['group'] = isset($groups[$item['item_id']]) ? $groups[$item['item_id']] : null;
        }
        return $page;
    }

    public static function addHistory(int $type, int $id, int $user_id,
                                      int $message = 0, int $count = 0) {
        static::addIfHistory($type, $id, $user_id, $message, 0);
        if ($type > 0) {
            return;
        }
        static::addIfHistory($type, $user_id, $id ,$message, $count < 1 ? 1 : $count);
    }

    public static function removeHistory(int $type, int $id, int $user_id) {
        ChatHistoryModel::where('item_type', $type)
            ->where('item_id', $id)
            ->where('user_id', $user_id)->delete();
    }

    public static function removeIdHistory(int $id) {
        ChatHistoryModel::where('id', $id)
            ->where('user_id', auth()->id())->delete();
    }

    public static function clearUnread(int $type, int $id, int $user_id) {
        ChatHistoryModel::where('item_type', $type)
            ->where('item_id', $id)
            ->where('user_id', $user_id)->update([
                'unread_count' => 0,
            ]);
    }

    protected static function addIfHistory(int $type, int $id, int $user_id,
                                           int $message = 0, int $messageCount = 0) {
        $count = ChatHistoryModel::where('item_type', $type)
            ->where('item_id', $id)
            ->where('user_id', $user_id)->count();
        if ($count > 0) {
            ChatHistoryModel::where('item_type', $type)
                ->where('item_id', $id)
                ->where('user_id', $user_id)->update([
                    'unread_count=unread_count+'.$messageCount,
                    'last_message' => $message
                ]);
            return;
        }
        ChatHistoryModel::create([
            'item_type' => $type,
            'item_id' => $id,
            'user_id' => $user_id,
            'unread_count' => $messageCount,
            'last_message' => $message,
        ]);
    }

    protected static function getLastMessage(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $users = MessageModel::query()->whereIn('id', $ids)
            ->get();
        $items = [];
        foreach ($users as $item) {
            $items[$item['id']] = $item;
        }
        return $items;
    }

    protected static function getGroup(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $users = GroupModel::whereIn('id', $ids)
            ->get();
        $items = [];
        foreach ($users as $item) {
            $items[$item['id']] = $item;
        }
        return $items;
    }

    protected static function getFriend(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $users = FriendModel::whereIn('user_id', $ids)
            ->where('belong_id', auth()->id())
            ->get();
        $items = [];
        foreach ($users as $item) {
            $items[$item['user_id']] = $item;
        }
        return  $items;
    }

    protected static function getUsers(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $users = UserSimpleModel::whereIn('id', $ids)
            ->get();
        $items = [];
        foreach ($users as $item) {
            $items[$item['id']] = $item;
        }
        return  $items;
    }
}