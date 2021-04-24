<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;

class BulletinRepository {

    /**
     * 获取消息列表
     * @param string $keywords
     * @param int $status
     * @return mixed
     * @throws Exception
     */
    public static function getList(string $keywords = '', int $status = 0) {
        return BulletinUserModel::with('bulletin')
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
            ->where('user_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
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
    public static function system(array|int $user, string $title, string $content, int $type = 99, array $extraRule = []) {
        return static::send($user, $title, $content, $type, 0, $extraRule);
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
    public static function send(array|int $user, string $title, string $content, int $type = 0, int $sender = 0, array $extraRule = []) {
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
        return BulletinUserModel::query()->insert(['bulletin_id', 'user_id', 'created_at'], $data);
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