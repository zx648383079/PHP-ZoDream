<?php
namespace Module\Chat\Service;

use Module\Chat\Domain\Model\FriendGroupModel;

class HomeController extends Controller {

    public $layout = 'main';

    public function indexAction() {
        $user = auth()->user();
        $group_list = FriendGroupModel::with('friends')
            ->whereIn('user_id', [0, auth()->id()])->all();
        return $this->show(compact('user', 'group_list'));
    }

}