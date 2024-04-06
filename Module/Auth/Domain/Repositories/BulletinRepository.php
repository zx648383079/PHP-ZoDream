<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\SEO\Domain\Repositories\EmojiRepository;

class BulletinRepository {

    const TYPE_AT = 7;
    const TYPE_COMMENT = 8;
    const TYPE_AGREE = 6;
    const TYPE_OTHER = 99;

    const SYSTEM_USER = [
        'name' => '[System]',
        'icon' => 'S',
        'avatar' => '/assets/images/favicon.png',
        'id' => 0
    ];

    /**
     * 获取消息列表
     * @param string $keywords
     * @param int $status
     * @param int $user
     * @return mixed
     * @throws Exception
     */
    public static function getList(string $keywords = '', int $status = 0, int $user = 0, int $lastId = 0) {
        $page = BulletinUserModel::with('bulletin')
            ->when(!empty($keywords), function ($query) {
                $ids = SearchModel::searchWhere(BulletinModel::query(), 'title')->pluck('id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('bulletin_id', $ids);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status - 1);
            })
            ->when($user != 0, function ($query) use ($user) {
                $bulletinId = static::getUserBulletin($user);
                if (empty($bulletinId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('bulletin_id', $bulletinId);
            })
            ->when($lastId > 0, function ($query) use ($lastId) {
                $query->where('id', '<', $lastId);
            })
            ->where('user_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
        $systemUser = static::SYSTEM_USER;
        $systemUser['avatar'] = url()->asset($systemUser['avatar']);
        $deleteUser = [
            'name' => '[用户已删除]',
            'icon' => '删',
            'avatar' => $systemUser['avatar'],
            'id' => -9
        ];
        foreach ($page as $item) {
            if ($item->bulletin->user) {
                continue;
            }
            if ($item->bulletin->user_id < 1) {
                $item->bulletin->user = $systemUser;
                continue;
            }
            $deleteUser['id'] = $item->bulletin->user_id;
            $item->bulletin->user = $deleteUser;
        }
        return $page;
    }

    public static function userList() {
        $systemUser = static::SYSTEM_USER;
        $systemUser['avatar'] = url()->asset($systemUser['avatar']);
        $bulletinId = BulletinUserModel::where('user_id', auth()->id())
            ->pluck('bulletin_id');
        if (empty($bulletinId)) {
            return [$systemUser];
        }
        $userId = BulletinModel::whereIn('id', $bulletinId)
            ->where('user_id', '>', 0)
            ->selectRaw('DISTINCT user_id')->pluck('user_id');
        if (empty($userId)) {
            return [$systemUser];
        }
        $users = UserSimpleModel::whereIn('id', $userId)->get();
        array_unshift($users, $systemUser);
        return $users;
    }

    public static function create(array $data) {
        if ($data['user'] < 1) {
            throw new Exception('操作错误');
        }
        return static::message(intval($data['user']),
            $data['title'] ?? '消息', $data['content'], static::TYPE_OTHER, EmojiRepository::renderRule($data['content']));
    }

    public static function doAction(int $id, array|string $action) {
        if ($id < 0) {
            throw new Exception('操作错误');
        }
        foreach ((array)$action as $key => $val) {
            // 做一些屏蔽用户的操作
        }
    }

    /**
     * 取一条消息
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public static function read(int $id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            throw new Exception('消息不存在');
        }
        $model->status = BulletinUserModel::READ;
        $model->save();
        return $model;
    }

    /**
     * 标记全部已读
     * @throws Exception
     */
    public static function readAll() {
        BulletinUserModel::where('user_id', auth()->id())
            ->where('status', 0)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
    }

    /**
     * 删除一条消息
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public static function remove(int $id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            throw new Exception('消息不存在');
        }
        $model->delete();
        $count = BulletinUserModel::where('user_id', '<>', auth()->id())
            ->where('bulletin_id', $id)->count();
        if ($count < 1) {
            BulletinModel::where('id', $id)->delete();
        }
    }

    protected static function getUserBulletin(int $user): array {
        if ($user < 1) {
            $user = 0;
        }
        $bulletinId = BulletinUserModel::where('user_id', auth()->id())
            ->pluck('bulletin_id');
        if (empty($bulletinId)) {
            return [];
        }
        return BulletinModel::whereIn('id', $bulletinId)->where('user_id', $user)->pluck('id');
    }

    public static function removeUser(int $user) {
        if ($user < 1) {
            throw new Exception('系统消息无法删除');
        }
        $bulletinId = static::getUserBulletin($user);
        if (empty($bulletinId)) {
            return;
        }
        BulletinUserModel::where('user_id', auth()->id())
            ->whereIn('bulletin_id', $bulletinId)->delete();
    }

    public static function sendAt(array|int $user, string $title, string $link) {
        return static::sendLink($user, $title, $link, static::TYPE_AT);
    }

    public static function sendLink(array|int $user, string $title, string $link, int $type = self::TYPE_AT) {
        $tag = '[查看]';
        return static::message($user, $title, $tag, $type, [
            LinkRule::formatLink($tag, $link)
        ]);
    }

    /**
     * SEND MESSAGE TO ANY USERS
     *
     * @param array|int $user
     * @param string $title
     * @param string $content
     * @param int $type
     * @param array $extraRule
     * @return int
     * @throws Exception
     */
    public static function message(array|int $user, string $title, string $content, int $type = 0, array $extraRule = []) {
        return static::send($user, htmlspecialchars($title), htmlspecialchars($content), $type, auth()->id(), $extraRule);
    }

    /**
     * 发送系统消息
     * @param $user
     * @param string $title
     * @param string $content
     * @param int $type
     * @param array $extraRule
     * @return int
     * @throws Exception
     */
    public static function system(array|int $user, string $title, string $content,
                                  int $type = 99, array $extraRule = []): int {
        return static::send($user, $title, $content, $type, 0, $extraRule);
    }

    /**
     * 发送给管理员
     * @param string $title
     * @param string $content
     * @param int $type
     * @param array $extraRule
     * @return int
     * @throws Exception
     */
    public static function sendAdministrator(string $title,
                                          string $content, int $type = 99, array $extraRule = []): int {
        return static::send(1, $title, $content, $type, 0, $extraRule);
    }

    /**
     * 发送消息
     * @param array|int $user
     * @param string $title
     * @param string $content
     * @param int $type
     * @param int $sender
     * @param array $extraRule
     * @return int
     * @throws Exception
     */
    public static function send(array|int $user, string $title, string $content, int $type = 0,
                                int $sender = 0, array $extraRule = []): int {
        if (empty($user)) {
            return 0;
        }
        $bulletin = new BulletinModel();
        $bulletin->title = $title;
        $bulletin->content = $content;
        $bulletin->type = $type;
        $bulletin->create_at = time();
        $bulletin->user_id = $sender;
        $bulletin->extra_rule = $extraRule;
        if (!$bulletin->save()) {
            return 0;
        }
        $data = [];
        foreach ((array)$user as $item) {
            $data[] = [$bulletin->id, $item, time()];
        }
        return (int)BulletinUserModel::query()->insert(['bulletin_id', 'user_id', 'created_at'], $data);
    }

    /**
     * 获取未读消息
     * @return int
     * @throws \Exception
     */
    public static function unreadCount(): int {
        if (auth()->guest()) {
            return 0;
        }
        return BulletinUserModel::where('user_id', auth()->id())->where('status', 0)->count();
    }
}