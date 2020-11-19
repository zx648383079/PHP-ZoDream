<?php
namespace Module\Chat\Service;

use Module\Chat\Domain\Model\MessageModel;

class UserController extends Controller {
    public function indexAction() {
        $user = auth()->user();
        $new_count = MessageModel::where('user_id', $user->id)->where('status', MessageModel::STATUS_NONE)->count();
        return $this->renderData([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'brief' => '',
            'new_count' => $new_count
        ]);
    }

}