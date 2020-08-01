<?php
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\UserModel;

class WxRepository {
    public static function saveUser(array $info, string $wid) {
        $fans = FansModel::where('openid', $info['openid'])
            ->where('wid', $wid)->first();
        if (empty($fans)) {
            $fans = FansModel::create([
                'openid' => $info['openid'],
                'wid' => $wid,
                'status' => $info['subscribe'] > 0 ? FansModel::STATUS_SUBSCRIBED
                    : FansModel::STATUS_UNSUBSCRIBED
            ]);
        }
        if ($info['subscribe'] < 1 || empty($fans)) {
            return;
        }
        $user = UserModel::findOrNew($fans->id);
        $user->set([
            'id' => $fans->id,
            'openid' => $info['openid'],
            'nickname' => $info['nickname'],
            'sex' => $info['sex'],
            'city' => $info['city'],
            'country' => $info['country'],
            'province' => $info['province'],
            'language' => $info['language'],
            'avatar' => $info['headimgurl'],
            'subscribe_time' => $info['subscribe_time'],
            'remark' => $info['remark'],
            'union_id' => isset($info['unionid']) ? $info['unionid'] : '', // 测试号无此项
            'group_id' => $info['groupid'],
        ]);
        $user->save();
    }
}