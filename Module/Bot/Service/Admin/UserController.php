<?php
namespace Module\Bot\Service\Admin;

use Module\Bot\Domain\Repositories\FollowRepository;

class UserController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(bool $blacklist = false) {
        $model_list = FollowRepository::getList($this->botId(), '', 0, $blacklist);
        return $this->show(compact('model_list'));
    }

    public function refreshAction() {
        try {
            FollowRepository::async($this->botId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

}