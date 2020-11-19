<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\WxRepository;
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
                WxRepository::saveUser($item, $this->weChatId());
            }
            if (empty($openid_list['next_openid'])) {
                break;
            }
            $next_openid = $openid_list['next_openid'];
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

}