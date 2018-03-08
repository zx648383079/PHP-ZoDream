<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\FansModel;

class UserController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction() {
        $model_list = FansModel::with('user')->where('wid', $this->weChatId())->page();
        return $this->show(compact('model_list'));
    }
}