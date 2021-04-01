<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Domain\Repositories\FileRepository;
use Infrastructure\LinkRule;
use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\GroupUserModel;
use Module\Chat\Domain\Model\MessageModel;
use Module\SEO\Domain\Repositories\EmojiRepository;

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
        $time = time() + 1;
        return [
            'message_count' => $message,
            'apply_count' => $apply,
            'messages' => [
                $user => $messages,
            ],
            'time' => $time
        ];
    }


    public static function sendText(int $itemType, int $id, string $content) {
        $extraRules = array_merge(
            // 只有群才能at 群人名
            // self::at($content, $model->id),
            EmojiRepository::renderRule($content)
        );
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_TEXT, $content, $extraRules);
    }

    public static function sendImage(int $itemType, int $id, string $fieldKey = 'file') {
        $images = FileRepository::uploadImages($fieldKey);
        return static::sendBatch($itemType, $id, auth()->id(), array_map(function (array $item) {
            return [
                'type' => MessageModel::TYPE_IMAGE,
                'content' => '[图片]',
                'extra_rule' => [
                    LinkRule::formatImage('[图片]', $item['url'])
                ]
            ];
        }, $images));
    }

    public static function sendFile(int $itemType, int $id, string $fieldKey = 'file') {
        $file = FileRepository::uploadFile($fieldKey);
        $word = sprintf('[%s]', $file['original']);
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_FILE, $word, [
                LinkRule::formatFile($word, $file['url'])
            ]);
    }

    public static function sendVideo(int $itemType, int $id, string $fieldKey = 'file') {
        $file = FileRepository::uploadVideo($fieldKey);
        $word = '[视频]';
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_FILE, $word, [
                LinkRule::formatFile($word, $file['url'])
            ]);
    }

    public static function sendAudio(int $itemType, int $id, string $fieldKey = 'file') {
        $file = FileRepository::uploadVideo($fieldKey);
        $word = '[语音]';
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_FILE, $word, [
                LinkRule::formatFile($word, $file['url'])
            ]);
    }

    public static function send(int $itemType, int $id, int $user, int $type,
                                string $content, array $extraRule = []) {
        if (empty($content)) {
            throw new \Exception('内容不能为空');
        }
        $items = static::sendBatch($itemType, $id, $user, [
            [
                'type' => $type,
                'content' => $content,
                'extra_rule' => $extraRule
            ]
        ]);
        if (empty($items)) {
            throw new \Exception('发送失败');
        }
        return current($items);
    }

    /**
     * 插入多条
     * @param int $itemType
     * @param int $id
     * @param int $user
     * @param array $data  [[type, content, extra_rule]]
     * @return array
     */
    public static function sendBatch(int $itemType, int $id, int $user, array $data): array {
        $items = [];
        foreach ($data as $item) {
            $message = MessageModel::create([
                'type' => isset($item['type']) ? $item['type'] : MessageModel::TYPE_TEXT,
                'content' => $item['content'],
                'item_id' => isset($item['item_id']) ? $item['item_id'] : 0,
                'receive_id' => $itemType < 1 ? $id : 0,
                'group_id' => $itemType < 1 ? 0 : $id,
                'user_id' => $user,
                'status' => MessageModel::STATUS_NONE,
                'deleted_at' => 0,
                'extra_rule' => isset($data['extra_rule']) ? $data['extra_rule'] : '',
            ]);
            if (!empty($message)) {
                $items[] = $message;
            }
        }
        if (empty($items)) {
            throw new  \Exception('发送失败');
        }
        ChatRepository::addHistory($itemType, $id, $user, $items[count($items) - 1]['id'], count($items));
        return $items;
    }


    public static function getList(int $itemType, int $id, int $startTime) {
        if (empty($startTime)) {
            $data = MessageModel::with('user', 'receive')
                ->when($itemType > 0, function ($query) use ($id) {
                    $query->where('group_id', $id);
                }, function ($query) use ($id) {
                    $query->where('group_id', 0)
                    ->where('receive_id', $id);
                })
                ->orderBy('created_at', 'desc')->limit(10)->get();
            $data = array_reverse($data);
        } else {
            $data = MessageModel::with('user', 'receive')
                ->when($itemType > 0, function ($query) use ($id) {
                    $query->where('group_id', $id);
                }, function ($query) use ($id) {
                    $query->where('group_id', 0)
                        ->where('receive_id', $id);
                })
                ->where('created_at', '>=', $startTime)
                ->orderBy('created_at', 'asc')
                ->get();
        }
        $next_time = time() + 1;
        if (empty($data) || !$itemType > 0) {
            return compact('next_time', 'data');
        }
        $userIds = [];
        foreach ($data as $item) {
            $userIds[] = $item['user_id'];
            $userIds[] = $item['receive_id'];
        }
        $userIds = array_unique($userIds);
        $users = static::getGroupUser($id, $userIds);
        foreach ($data as $item) {
            $item['user'] = static::formatGroupUser($users, $item['user_id'], $item['user']);
            $item['receive'] = static::formatGroupUser($users, $item['receive_id'], $item['receive']);;
        }
        return compact('next_time', 'data');
    }

    protected static function formatGroupUser(array $groupUsers, int $id, array $user) {
        if (isset($groupUsers[$id])) {
            return array_merge(
                $groupUsers[$id],
                [
                    'user' => $user
                ]
            );
        }
        return [
            'name' => '[]',
            'user' => $user,
        ];
    }

    protected static function getGroupUser(int $id, array $ids) {
        if (empty($ids)) {
            return [];
        }
        $users = GroupUserModel::whereIn('user_id', $ids)
            ->where('group_id', $id)
            ->get();
        $items = [];
        foreach ($users as $item) {
            $items[$item['user_id']] = $item->toArray();
        }
        return $items;
    }
}