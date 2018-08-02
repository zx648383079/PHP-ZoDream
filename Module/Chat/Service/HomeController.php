<?php
namespace Module\Chat\Service;


use Module\Chat\Domain\Model\FriendGroupModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;

class HomeController extends ModuleController {

    public $layout = 'main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $user = auth()->user();
        $group_list = FriendGroupModel::with('friends')
            ->whereIn('user_id', [0, auth()->id()])->all();
        return $this->show(compact('user', 'group_list'));
    }

}