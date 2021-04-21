<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;


use Module\Chat\Domain\Model\MessageModel;

class UserRepository {

    public static function profile() {
        $user = auth()->user();
        $new_count = MessageModel::where('receive_id', $user->id)->where('status', MessageModel::STATUS_NONE)->count();
        return [
            'name' => $user->name,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ],
            'signature' => '',
            'new_count' => $new_count
        ];
    }
}