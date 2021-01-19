<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\Chat\Domain\Model\MessageModel;

class UserController extends Controller {
    public function indexAction() {
        $user = auth()->user();
        $new_count = MessageModel::where('user_id', $user->id)->where('status', MessageModel::STATUS_NONE)->count();
        return $this->render([
            'name' => $user->name,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ],
            'signature' => '',
            'new_count' => $new_count
        ]);
    }

}