<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\User;

class UserController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction($backlist = null) {
        $model_list = FansModel::with('user')->where('wid', $this->weChatId())
            ->when(!empty($backlist), function ($query) {
                $query->where('is_black', 1);
            })->page();
        return $this->show(compact('model_list'));
    }

    public function refreshAction() {
        $total = 0;
        $next_openid = null;
        /** @var User $api */
        $api = WeChatModel::find($this->weChatId())
            ->sdk(User::class);
        while (true) {
            $openid_list = $api->userList($next_openid);
            if (empty($openid_list['data']['openid'])) {
                break;
            }
            $data = $api->usersInfo($openid_list['data']['openid']);
            foreach ($data['user_info_list'] as $item) {
                $this->saveUser($item);
            }
            if (empty($openid_list['next_openid'])) {
                break;
            }
            $next_openid = $openid_list['next_openid'];
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    private function saveUser($info) {
        $fans = FansModel::where('openid', $info['openid'])
            ->where('wid', $this->weChatId())->first();
        if (empty($fans)) {
            $fans = FansModel::create([
                'openid' => $info['openid'],
                'wid' => $this->weChatId(),
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