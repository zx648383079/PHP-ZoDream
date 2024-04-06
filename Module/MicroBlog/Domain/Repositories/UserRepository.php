<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\Auth\Domain\Repositories\UserRepository as Auth;

class UserRepository {

    public static function get(int $id): array {
        $micro_count = MicroBlogModel::where('user_id', $id)
            ->count();
        if ($micro_count < 1) {
            throw new \Exception('用户还没有内容');
        }
        $user = Auth::getPublicProfile($id, 'following_count,follower_count,follow_status');
        if (empty($user)) {
            throw new \Exception('用户已注销');
        }
        return array_merge($user, compact('micro_count'));
    }
}
