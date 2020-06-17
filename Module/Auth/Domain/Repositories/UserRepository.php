<?php
namespace Module\Auth\Domain\Repositories;


use Module\Auth\Domain\Model\UserModel;

class UserRepository {

    public static function getCurrentProfile() {
        /** @var UserModel $user */
        $user = auth()->user();
        $data = $user->toArray();
        $data['is_admin'] = auth()->user()->isAdministrator() || $user->hasRole('shop_admin');
        return $data;
    }
}