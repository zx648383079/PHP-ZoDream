<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\FollowRepository;
use Module\WeChat\Domain\Repositories\WxRepository;
use phpDocumentor\Reflection\Types\This;
use Zodream\ThirdParty\WeChat\User;

class UserController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(bool $blacklist = false) {
        $model_list = FollowRepository::getList($this->weChatId(), '', $blacklist);
        return $this->show(compact('model_list'));
    }

    public function refreshAction() {
        try {
            FollowRepository::async($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

}