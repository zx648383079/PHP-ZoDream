<?php
namespace Module\Chat\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\Chat\Domain\Model\FriendGroupModel;
use Module\Chat\Domain\Model\FriendModel;
use Module\Chat\Domain\Model\MessageModel;

class FriendController extends Controller {

    public function indexAction() {
        $data = FriendGroupModel::with('friends')
            ->whereIn('user_id', [0, auth()->id()])->all();
        return $this->jsonSuccess($data);
    }

    public function searchAction($keywords = null) {
        $data = UserModel::when(!empty($keywords), function ($query) {
            FriendModel::search($query, 'name');
        })->page();
        return $this->jsonSuccess($data);
    }

    public function messageAction($user) {
        $data = MessageModel::where('user_id', $user)->page();
        return $this->jsonSuccess($data);
    }
}