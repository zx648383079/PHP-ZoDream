<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Domain\Repositories\FileRepository;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;
use Module\Chat\Domain\Model\MessageModel;
use Module\SEO\Domain\Repositories\EmojiRepository;

class MessageRepository {

    public static function ping(int $time = 0, int $type = 0, int $id = 0) {
        $message = MessageModel::where('receive_id', auth()->id())
            ->when($time > 0, function ($query) use ($time) {
                $query->where('created_at', '>=', $time);
            })
            ->where('status', MessageModel::STATUS_NONE)->count();
        $apply = ApplyModel::where('item_id', auth()->id())
            ->where('item_type', 0)
            ->when($time > 0, function ($query) use ($time) {
                $query->where('created_at', '>', $time);
            })->where('status', 0)->count();
        $messages = [];
        if ($id > 0) {
            $messages = MessageModel::with('user', 'receive')
                ->when($type > 0, function ($query) use ($id) {
                    $query->where('group_id', $id);
                }, function ($query) use ($id) {
                    $query->where('group_id', 0)
                        ->where('receive_id', auth()->id())
                        ->where('user_id', $id);
                })
                ->where('created_at', '>=', $time)->get();
        }
        $time = time() + 1;
        return [
            'message_count' => $message,
            'apply_count' => $apply,
            'data' => empty($messages) ? [] : [
                [
                    'type' => $type,
                    'id' => $id,
                    'items' => $messages
                ],
            ],
            'next_time' => $time
        ];
    }


    public static function sendText(int $itemType, int $id, string $content) {
        $extraRules = array_merge(
            [],
            // 只有群才能at 群人名
            $itemType > 0 ? self::at($content, $id) : [],
            EmojiRepository::renderRule($content)
        );
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_TEXT, $content, $extraRules);
    }

    public static function at(string $content, int $group): array {
        if (empty($content) || !str_contains($content, '@')) {
            return [];
        }
        if (!preg_match_all('/@(\S+?)\s/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }
        $names = array_column($matches, 0, 1);
        $users = GroupUserModel::whereIn('name', array_keys($names))->asArray()->get();
        if (empty($users)) {
            return [];
        }
        $rules = [];
        $currentUser = auth()->id();
        $userIds = [];
        foreach ($users as $user) {
            if ($user['user_id'] != $currentUser) {
                $userIds[] = $user['user_id'];
            }
            $rules[] = LinkRule::formatUser($names[$user['name']], intval($user['user_id']));
        }
        if (!empty($userIds)) {
            $group = GroupModel::find($group);
            BulletinRepository::message($userIds,
                sprintf('我在群【%s】提到了你', $group->name), '[回复]', 88, [
                    LinkRule::formatLink('[回复]', 'chat')
                ]);
        }
        return $rules;
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
            MessageModel::TYPE_VIDEO, $word, [
                LinkRule::formatFile($word, $file['url'])
            ]);
    }

    public static function sendAudio(int $itemType, int $id, string $fieldKey = 'file') {
        $file = FileRepository::uploadVideo($fieldKey);
        $word = '[语音]';
        return static::send($itemType, $id, auth()->id(),
            MessageModel::TYPE_VOICE, $word, [
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
                'extra_rule' => isset($item['extra_rule']) ? $item['extra_rule'] : '',
            ]);
            if (!empty($message)) {
                $items[] = $message;
            }
        }
        if (empty($items)) {
            throw new \Exception('发送失败');
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

    /**
     * 消息撤回
     * @param int $id
     * @throws \Exception
     */
    public static function revoke(int $id) {
        $model = MessageModel::find($id);
        if (!empty($model)) {
            throw new \Exception('消息错误');
        }
        if ($model->user_id !== auth()->id()) {
            throw new \Exception('操作错误');
        }
        if ($model->getAttributeSource('created_at') < time() - 120) {
            throw new \Exception('超过两分钟无法撤回');
        }
        $model->delete();
    }
}