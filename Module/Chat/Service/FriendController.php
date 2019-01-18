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
        $groups = FriendGroupModel::where('user_id', auth()->id())->pluck('id');
        $exclude = empty($groups) ? [] : FriendModel::whereIn('group_id', $groups)
            ->pluck('user_id');
        $exclude[] = auth()->id();
        $data = UserModel::when(!empty($keywords), function ($query) {
            FriendModel::search($query, 'name');
        })->whereNotIn('id', $exclude)->page();
        return $this->jsonSuccess($data);
    }

    public function messageAction($user) {
        $data = MessageModel::with('user', 'receive')->where(function($query) use ($user) {
            $query->where('user_id', $user)->where('receive_id', auth()->id());
        })->orWhere(function($query) use ($user) {
            $query->where('receive_id', $user)->where('user_id', auth()->id());
        })->page();
        return $this->jsonSuccess($data);
    }

    public function sendMessageAction($user, $content) {
        $data = MessageModel::create([
            'receive_id' => $user,
            'content' => $content,
            'user_id' => auth()->id()
        ]);
        return $this->jsonSuccess($data);
    }
}