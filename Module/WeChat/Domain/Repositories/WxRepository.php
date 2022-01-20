<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\User;

class WxRepository {
    public static function saveUser(array $info, string|int $wid) {
        $user = UserModel::where('openid', $info['openid'])
            ->where('wid', $wid)->first();
        if (empty($user)) {
            $user = new UserModel();
        }
        $user->set([
            'openid' => $info['openid'],
            'nickname' => $info['nickname'],
            'sex' => $info['sex'],
            'city' => $info['city'],
            'country' => $info['country'],
            'province' => $info['province'],
            'language' => $info['language'],
            'avatar' => $info['headimgurl'],
            'subscribe_at' => $info['subscribe_time'],
            'remark' => $info['remark'],
            'union_id' => $info['unionid'] ?? '', // 测试号无此项
            'group_id' => $info['groupid'],
            'wid' => $wid,
            'status' => $info['subscribe'] > 0 ? UserModel::STATUS_SUBSCRIBED
                : UserModel::STATUS_UNSUBSCRIBED
        ]);
        $user->save();
    }

    public static function subscribe(WeChatModel $account, string $openid) {
        $model = UserModel::where('openid', $openid)
            ->where('wid', $account->id)->first();
        if (empty($model)) {
            $model = new UserModel([
                'openid' => $openid,
                'wid' => $account->id,
            ]);
        }
        $model->status = UserModel::STATUS_SUBSCRIBED;
        $model->subscribe_at = time();
        $info = $account->sdk(User::class)->userInfo($openid);
        if (empty($info)) {
            $model->save();
            return;
        }
        $model->set([
            'nickname' => $info['nickname'],
            'sex' => $info['sex'],
            'city' => $info['city'],
            'country' => $info['country'],
            'province' => $info['province'],
            'language' => $info['language'],
            'avatar' => $info['headimgurl'],
            'subscribe_at' => $info['subscribe_time'],
            'remark' => $info['remark'],
            'union_id' => $info['unionid'] ?? '', // 测试号无此项
            'group_id' => $info['groupid'],
        ]);
        $model->save();
    }

    public static function unsubscribe(int $wid, string $openid) {
        UserModel::where('openid', $openid)
            ->where('wid', $wid)->update([
                'status' => UserModel::STATUS_UNSUBSCRIBED,
                'updated_at' => time()
            ]);
    }
}