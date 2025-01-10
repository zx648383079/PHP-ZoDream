<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\BanAccountModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;

final class BanRepository {

    const OAUTH_TYPE_MAPS = [
        OAuthModel::TYPE_QQ => AuthRepository::ACCOUNT_TYPE_OAUTH_QQ,
        OAuthModel::TYPE_WX => AuthRepository::ACCOUNT_TYPE_OAUTH_WX,
        OAuthModel::TYPE_WX_MINI => AuthRepository::ACCOUNT_TYPE_OAUTH_WX_MINI,
        OAuthModel::TYPE_ALIPAY => AuthRepository::ACCOUNT_TYPE_OAUTH_ALIPAY,
        OAuthModel::TYPE_TAOBAO => AuthRepository::ACCOUNT_TYPE_OAUTH_TAOBAO,
        OAuthModel::TYPE_WEIBO => AuthRepository::ACCOUNT_TYPE_OAUTH_WEIBO,
        'github' => AuthRepository::ACCOUNT_TYPE_OAUTH_GITHUB
    ];

    public static function banUser(int $userId) {
        if ($userId == auth()->id()) {
            throw new \Exception('不能拉黑自己');
        }
        $user = UserModel::where('id', $userId)
            ->first('email', 'mobile');
        if (empty($user)) {
            return;
        }
        self::ban($user['email'], AuthRepository::ACCOUNT_TYPE_EMAIL);
        self::ban($user['mobile'], AuthRepository::ACCOUNT_TYPE_MOBILE);
        $items = OAuthModel::where('user_id', $userId)->get();
        foreach ($items as $item) {
            $type = self::OAUTH_TYPE_MAPS[$item['vendor']];
            self::ban($item['identity'], $type, $item['platform_id']);
            self::ban($item['unionid'], $type, $item['platform_id']);
        }
        UserModel::where('id', $userId)->where('status', '>=', UserModel::STATUS_ACTIVE)->update([
            'status' => UserModel::STATUS_FROZEN,
        ]);
    }

    /**
     * 屏蔽
     * @param string $itemKey
     * @param int $itemType
     * @param int $platformId
     * @return void
     * @throws \Exception
     */
    public static function ban(string $itemKey, int $itemType = 0, int $platformId = 0) {
        if (empty($itemKey)) {
            return;
        }
        if (self::isBan($itemKey, $itemType, $platformId)) {
            return;
        }
        BanAccountModel::create([
            'user_id' => auth()->id(),
            'item_key' => $itemKey,
            'item_type' => $itemType,
            'platform_id' => $platformId
        ]);
    }

    public static function isBan(string $itemKey, int $itemType = -1, int $platformId = 0): bool {
        if ($itemType < 0) {
            return BanAccountModel::where('item_key', $itemKey)->count() > 0;
        }
        return BanAccountModel::where('item_key', $itemKey)
                ->where('item_type', $itemType)->where('platform_id', $platformId)->count() > 0;
    }

    public static function isBanOAuth(string $openid, string|null $unionId, string $vendor, int $platformId): bool {
        $type = self::OAUTH_TYPE_MAPS[$vendor];
        if (!empty($openid) && self::isBan($openid, $type, $platformId)) {
            return true;
        }
        if (!empty($unionId) && self::isBan($unionId, $type, $platformId)) {
            return true;
        }
        return false;
    }

    /**
     * 取消屏蔽
     * @param string $itemKey
     * @param int $itemType
     * @param int $platformId
     */
    public static function unban(string $itemKey, int $itemType = -1, int $platformId = 0): void {
        if ($itemType < 0) {
            BanAccountModel::where('item_key', $itemKey)->delete();
            return;
        }
        BanAccountModel::where('item_key', $itemKey)
                ->where('item_type', $itemType)->where('platform_id', $platformId)->delete();
    }

    public static function getList(string $keywords) {
        return BanAccountModel::when(!empty($keywords), function ($query) use ($keywords) {
            $query->where('item_key', $keywords);
        })->page();
    }

    public static function remove(int $id) {
        BanAccountModel::where('id', $id)->delete();
    }

    public static function removeUser(int $userId) {
        $user = UserModel::where('id', $userId)
            ->first('email', 'mobile');
        if (empty($user)) {
            return;
        }
        self::unban($user['email'], AuthRepository::ACCOUNT_TYPE_EMAIL);
        self::unban($user['mobile'], AuthRepository::ACCOUNT_TYPE_MOBILE);
        $items = OAuthModel::where('user_id', $userId)->get();
        foreach ($items as $item) {
            $type = self::OAUTH_TYPE_MAPS[$item['vendor']];
            self::unban($item['identity'], $type, $item['platform_id']);
            self::unban($item['unionid'], $type, $item['platform_id']);
        }
        UserModel::where('id', $userId)->where('status', '<', UserModel::STATUS_ACTIVE)->update([
            'status' => UserModel::STATUS_ACTIVE,
        ]);
    }
}