<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\User;

class FollowRepository {

    public static function getList(int $wid, string $keywords = '', bool $blacklist = false) {
        AccountRepository::isSelf($wid);
        return FansModel::with('user')
            ->where('wid', $wid)
            ->when(!empty($blacklist), function ($query) {
                $query->where('is_black', 1);
            })->page();
    }

    public static function async(int $wid) {
        AccountRepository::isSelf($wid);
        $next_openid = null;
        /** @var User $api */
        $api = WeChatModel::findOrThrow($wid, '公众号错误')
            ->sdk(User::class);
        while (true) {
            $openid_list = $api->userList($next_openid);
            if (empty($openid_list['data']['openid'])) {
                break;
            }
            $data = $api->usersInfo($openid_list['data']['openid']);
            foreach ($data['user_info_list'] as $item) {
                WxRepository::saveUser($item, $wid);
            }
            if (empty($openid_list['next_openid'])) {
                break;
            }
            $next_openid = $openid_list['next_openid'];
        }
    }

    public static function searchFans(int $wid, string $keywords = '') {
        AccountRepository::isSelf($wid);
        return FansModel::query()->alias('f')
            ->where('f.wid', $wid)
            ->where('f.status', FansModel::STATUS_SUBSCRIBED)
            ->leftJoin(UserModel::tableName().' u', 'f.id', 'u.id')
            ->whereNotNull('u.id')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['u.nickname']);
            })
            ->asArray()
            ->get('f.id,u.nickname as name');
    }

    public static function update(int $id, array $data) {
        $model = FansModel::findOrThrow($id, '会员不存在');
        AccountRepository::isSelf($model->wid);
        return ModelHelper::updateField($model, ['is_black', 'name'], $data);
    }

}