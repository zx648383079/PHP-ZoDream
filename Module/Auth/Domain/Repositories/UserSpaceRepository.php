<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Constants;
use Exception;
use Module\Auth\Domain\Model\UserModel;
use Module\Contact\Domain\Repositories\ReportRepository;

final class UserSpaceRepository {

    public static function get(int $userId): array {
        $user = UserRepository::getPublicProfile($userId,
            'following_count,follower_count,follow_status,mark_status');
        if (empty($user)) {
            throw new \Exception('用户已注销');
        }
        $user['background'] = url()->asset('assets/images/banner.jpg');
        return $user;
    }

    /**
     * 关注
     * @param int $user
     * @return int
     * @throws Exception
     */
    public static function toggleFollow(int $user): int {
        if (auth()->guest()) {
            return 0;
        }
        $status = RelationshipRepository::toggle($user, RelationshipRepository::TYPE_FOLLOWING);
        if ($status < 1) {
            return 0;
        }
        return RelationshipRepository::userAlsoIs(auth()->id(), $user, RelationshipRepository::TYPE_FOLLOWING)
            ? 2 : 1;
    }

    /**
     * 拉黑
     * @param int $user
     * @return int
     * @throws Exception
     */
    public static function toggleMark(int $user): int {
        if (auth()->guest()) {
            return 0;
        }
        $status = RelationshipRepository::toggle($user, RelationshipRepository::TYPE_BLOCKING);
        return $status < 1 ? 0 : 1;
    }

    /**
     * 举报
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public static function report(int $userId, string $reason): void {
        if (auth()->guest()) {
            throw new Exception(__('Please log in first'));
        }
        if (auth()->id() === $userId) {
            throw new Exception('error');
        }
        $user = UserModel::findOrThrow($userId, 'user is error');
        ReportRepository::quickCreate(Constants::TYPE_USER,
            $userId, sprintf('举报【%s】：%s', $user->name, $reason), '举报用户');
        BulletinRepository::sendAdministrator('举报用户',
            sprintf('举报人：%s；被举报人：%s；举报理由：%s', auth()->user()->name, $user->name,
                $reason), 98,
        );
    }
}