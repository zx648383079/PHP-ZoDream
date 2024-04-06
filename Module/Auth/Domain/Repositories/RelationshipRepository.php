<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Entities\UserRelationshipEntity;

final class RelationshipRepository {

    const TYPE_FOLLOWING = 1; // 关注
    const TYPE_BLOCKING = 5; // 屏蔽用户

    /**
     * 判断关系是
     * @param int $me
     * @param int $user
     * @param int $type
     * @return bool
     */
    public static function is(int $me, int $user, int $type): bool {
        return UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->where('type', $type)->count() > 0;
    }

    public static function count(int $user, int $type): int {
        return UserRelationshipEntity::where('user_id', $user)->where('type', $type)->count();
    }

    public static function followingCount(int $user): int {
        return self::count($user, self::TYPE_FOLLOWING);
    }

    public static function followerCount(int $user): int {
        return UserRelationshipEntity::where('link_id', $user)->where('type', self::TYPE_FOLLOWING)->count();
    }

    /**
     * 添加
     * @param int $user
     * @param int $type
     * @return void
     * @throws \Exception
     */
    public static function bind(int $user, int $type) {
        $me = auth()->id();
        if ($me === $user) {
            throw new \Exception('can link self');
        }
        $log = UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->first();
        if (empty($log)) {
            UserRelationshipEntity::create([
                'user_id' => $me,
                'link_id' => $user,
                'type' => $type,
                'created_at' => time(),
            ]);
            return;
        }
        if ($log->type === $type) {
            return;
        }
        UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->update([
                'type' => $type,
                'created_at' => time(),
            ]);
    }

    /**
     * 取消操作
     * @param int $user
     * @param int $type
     * @return void
     * @throws \Exception
     */
    public static function unbind(int $user, int $type) {
        $me = auth()->id();
        if ($me === $user) {
            return;
        }
        UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->where('type', $type)
            ->delete();
    }

    /**
     * 变更为
     * @param int $user
     * @param int $type
     * @return int // {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public static function toggle(int $user, int $type) {
        $me = auth()->id();
        if ($me === $user) {
            throw new \Exception('can link self');
        }
        $log = UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->first();
        if (empty($log)) {
            UserRelationshipEntity::create([
                'user_id' => $me,
                'link_id' => $user,
                'type' => $type,
                'created_at' => time(),
            ]);
            return 2;
        }
        if ($log->type === $type) {
            UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
                ->delete();
            return 0;
        }
        UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->update([
                'type' => $type,
                'created_at' => time(),
            ]);
        return 1;
    }

    /**
     * 获取某一类型的双方关注状态
     * @param int $user
     * @param int $type
     * @return int  0 未关注 1 已关注，当对方未关注 2 已互相关注
     * @throws \Exception
     */
    public static function typeStatus(int $user, int $type): int {
        $me = auth()->id();
        if ($me === $user) {
            return 0;
        }
        $count = UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)
            ->where('type', $type)
            ->count();
        if ($count < 1) {
            return 0;
        }
        return self::userAlsoIs($me, $user, $type) ? 2 : 1;
    }

    public static function userAlsoIs(int $me, int $user, int $type): bool {
        return UserRelationshipEntity::where('user_id', $user)->where('link_id', $me)
            ->where('type', $type)
            ->count() > 0;
    }

    /**
     * 获取用户的所有关系
     * @param int $me
     * @param int $user
     * @return int[]
     */
    public static function relationship(int $me, int $user): array {
        $items = [
            'follow_status' => 0,
            'mark_status' => 0,
        ];
        if ($me === $user) {
            return $items;
        }
        $log = UserRelationshipEntity::where('user_id', $me)->where('link_id', $user)->first();
        if (empty($log)) {
            return $items;
        }
        $type = intval($log['type']);
        if ($type === self::TYPE_BLOCKING) {
            $items['mark_status'] = 1;
            return $items;
        }
        if ($type === self::TYPE_FOLLOWING) {
            $items['follow_status'] = self::userAlsoIs($me, $user, $type) ? 2 : 1;
            return $items;
        }
        return $items;
    }

    public static function containsRelationship(array $keys): bool {
        foreach ([
                     'follow_status',
                     'mark_status'
                 ] as $key) {
            if (in_array($key, $keys)) {
                return true;
            }
        }
        return false;
    }
}