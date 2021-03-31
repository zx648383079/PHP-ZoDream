<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;

class UserRepository {

    public static function get(int $id) {
        $micro_count = MicroBlogModel::where('user_id', $id)
            ->count();
        if ($micro_count < 1) {
            throw new \Exception('用户还没有内容');
        }
        $user = UserSimpleModel::where('id', $id)->first();
        if (empty($user)) {
            throw new \Exception('用户已注销');
        }
        return array_merge($user->toArray(), compact('micro_count'));
    }
}
