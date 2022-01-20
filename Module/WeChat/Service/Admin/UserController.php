<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Repositories\FollowRepository;

class UserController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(bool $blacklist = false) {
        $model_list = FollowRepository::getList($this->weChatId(), '', 0, $blacklist);
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